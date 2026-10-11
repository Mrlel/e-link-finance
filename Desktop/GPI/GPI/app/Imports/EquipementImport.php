<?php

namespace App\Imports;

use App\Models\Equipement;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class EquipementImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithEvents
{
    use SkipsErrors, SkipsFailures;

    // Les listes canoniques vivent sur App\Models\Equipement (CATEGORIES/NATURES/...) - source
    // unique de vérité partagée avec EquipementController, pour ne plus jamais laisser ces
    // copies diverger du schéma réel comme c'était le cas auparavant.

    protected $userId;
    protected $directionId;
    protected $baseUrl;

    protected $tempEquipements = [];
    protected array $rowWarnings = [];

    public function __construct($userId, $directionId, $baseUrl)
    {
        $this->userId = $userId;
        $this->directionId = $directionId;
        $this->baseUrl = $baseUrl;
    }

    // Normalisations effectuées silencieusement ou lignes ignorées faute de correspondance -
    // à afficher à l'admin en plus de failures()/errors().
    public function rowWarnings(): array
    {
        return $this->rowWarnings;
    }

    public function model(array $row)
    {
        $categorie = $this->matchEnum($row['categorie'] ?? null, Equipement::CATEGORIES);
        $nature = $this->matchEnum($row['nature'] ?? null, Equipement::NATURES);
        $etat = $this->matchEnum($row['etat'] ?? null, Equipement::ETATS);
        $statut = $this->matchEnum($row['statut'] ?? null, Equipement::STATUTS) ?? 'en stock';
        $sourceAcquisition = $this->matchEnum($row['source_acquisition'] ?? null, Equipement::SOURCES_ACQUISITION);
        $dateAcquis = $this->parseDate($row['date_acquis'] ?? null);

        // "nature" en base n'a que 6 valeurs larges, alors que les fichiers réels utilisent des
        // libellés plus riches ("Matériel informatique", "Équipement réseau"...) qui ne matchent
        // aucune valeur exacte même après normalisation de casse/accents. Plutôt que rejeter la
        // ligne, on tente un rapprochement par mot-clé puis on retombe sur "autre" (bucket prévu
        // pour ça) - jamais un rejet pour une simple différence de vocabulaire.
        if ($nature === null && !empty($row['nature'])) {
            $nature = $this->guessNature($this->fold($row['nature']));
            if ($nature !== null) {
                $this->rowWarnings[] = "Nature « {$row['nature']} » rapprochée de « {$nature} » pour le n° de série {$row['numero_serie']}.";
            }
        }
        if ($nature === null) {
            $nature = 'autre';
            $this->rowWarnings[] = "Nature « " . ($row['nature'] ?? '') . " » non reconnue pour le n° de série {$row['numero_serie']} - classée en « autre ».";
        }
        if ($etat === null) {
            $this->rowWarnings[] = "État « " . ($row['etat'] ?? '') . " » non reconnu pour le n° de série {$row['numero_serie']} - ligne ignorée.";
            return null;
        }

        if (!empty($row['categorie']) && $categorie === null) {
            $this->rowWarnings[] = "Catégorie « {$row['categorie']} » non reconnue pour le n° de série {$row['numero_serie']} - laissée vide.";
        }
        if (!empty($row['date_acquis']) && $dateAcquis === null) {
            $this->rowWarnings[] = "Date d'acquisition « {$row['date_acquis']} » illisible pour le n° de série {$row['numero_serie']} - laissée vide.";
        }

        $equipement = new Equipement([
            'des_equipement'     => $row['des_equipement'],
            'marque'             => $row['marque'],
            'modele'             => $row['modele'],
            'categorie'          => $categorie,
            'nature'             => $nature,
            'num_inventaire'     => $row['num_inventaire'] ?? null,
            'adresse_mac'        => $row['adresse_mac'] ?? null,
            'numero_serie'       => $row['numero_serie'],
            'date_acquis'        => $dateAcquis,
            'capacite'           => $this->parseInt($row['capacite'] ?? null),
            'ram'                => $this->parseInt($row['ram'] ?? null),
            'source_acquisition' => $sourceAcquisition,
            'nom_fn'             => $row['nom_fn'] ?? null,
            'processeur'         => $row['processeur'] ?? null,
            'systeme'            => $row['systeme'] ?? null,
            'etat'               => $etat,
            'statut'             => $statut,
            'user_id'            => $this->userId,
            'direction_id'       => $this->directionId,
        ]);

        $this->tempEquipements[] = $equipement;

        return $equipement;
    }

    // Tolère casse, accents et espaces superflus ("Bon", "HORS SERVICE", "Ecran"...) plutôt
    // que d'exiger une correspondance caractère pour caractère avec l'ENUM MySQL.
    private function matchEnum(?string $raw, array $canonical): ?string
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }

        $needle = $this->fold($raw);
        foreach ($canonical as $value) {
            if ($this->fold($value) === $needle) {
                return $value;
            }
        }

        return null;
    }

    // Rapprochement par mot-clé pour des libellés de "nature" plus riches que les 6 valeurs
    // ENUM (ex: fichiers réels utilisant "Matériel informatique", "Équipement réseau"...).
    // $folded doit déjà être passé par fold(). Ordre volontaire : "reseau"/"infrastructure"
    // avant "informati" pour qu'un "équipement réseau informatique" tombe dans reseaux.
    private function guessNature(string $folded): ?string
    {
        $keywords = [
            'reseau' => 'reseaux',
            'network' => 'reseaux',
            'infrastructure' => 'reseaux',
            'telephon' => 'telephonie et connectivite',
            'mobile' => 'telephonie et connectivite',
            'connectiv' => 'telephonie et connectivite',
            'audio' => 'multimedia',
            'video' => 'multimedia',
            'multimedia' => 'multimedia',
            'accessoire' => 'accesoires informatiques',
            'stockage' => 'informatiques et bureautiques',
            'bureautiq' => 'informatiques et bureautiques',
            'informati' => 'informatiques et bureautiques',
        ];

        foreach ($keywords as $needle => $canonical) {
            if (str_contains($folded, $needle)) {
                return $canonical;
            }
        }

        return null;
    }

    private function fold(string $value): string
    {
        return preg_replace('/\s+/', ' ', trim(Str::lower(Str::ascii($value))));
    }

    // Gère à la fois les dates sérielles Excel (cellule au format Date/nombre) et les dates
    // saisies en texte dans des formats variés (jj/mm/aaaa, aaaa-mm-jj...).
    private function parseDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                // Valeur numérique mais pas une date sérielle valide - on tente le parsing texte ci-dessous.
            }
        }

        $value = trim((string) $value);

        // jour/mois/année (usage français) en priorité : Carbon::parse() seul interprète
        // "10/03/2026" à l'américaine (mois/jour) et donnerait le 3 octobre au lieu du 10 mars.
        // Carbon::createFromFormat() lève une exception (au lieu de renvoyer false) sur un
        // format non reconnu - jamais laissé remonter hors de model(), sous peine de faire
        // planter tout l'import (Maatwebsite n'entoure pas l'appel à model() d'un try/catch).
        foreach (['d/m/Y', 'd-m-Y', 'd/m/y', 'd-m-y'] as $format) {
            try {
                return Carbon::createFromFormat('!' . $format, $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    // Tolère les valeurs formatées ("16 Go", "16,00") en ne gardant que les chiffres.
    private function parseInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }
        $digits = preg_replace('/[^\d]/', '', (string) $value);

        return $digits === '' ? null : (int) $digits;
    }

    public function rules(): array
    {
        return [
            'des_equipement' => 'required|string|max:255',
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'num_inventaire' => 'nullable|string|max:255',
            'adresse_mac' => 'nullable|string|max:255|unique:equipements,adresse_mac',
            'numero_serie' => 'required|string|unique:equipements,numero_serie',
            'nom_fn' => 'nullable|string|max:255',
            'processeur' => 'nullable|string|max:255',
            'systeme' => 'nullable|string|max:255',
            // categorie/nature/etat/statut/source_acquisition/date_acquis/capacite/ram ne sont
            // volontairement PAS contraints ici par in:/date/integer : ce sont des ENUM MySQL
            // stricts et Excel renvoie des formats hétérogènes (casse, accents, dates sérielles,
            // unités) - une règle stricte rejetterait des lignes par ailleurs valides pour un
            // simple écart de format. La normalisation et le rejet ciblé (uniquement si vraiment
            // aucune correspondance) se font dans model().
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                // On s'appuie sur l'instance déjà persistée (même objet que celui sauvegardé
                // par le import processor) plutôt que de la retrouver par num_inventaire, qui
                // n'est ni requis ni unique et pourrait pointer vers le mauvais équipement.
                foreach ($this->tempEquipements as $equipement) {
                    if (!$equipement->exists) {
                        continue;
                    }

                    $qrData = $this->baseUrl . route('equipement.details', $equipement->id, false);
                    $qrImage = QrCode::format('svg')->size(200)->generate($qrData);

                    $fileName = 'qrcodes/equipement_' . $equipement->id . '.svg';
                    Storage::disk('public')->put($fileName, $qrImage);

                    $equipement->qr_code = $fileName;
                    $equipement->save();
                }
            },
        ];
    }
}
