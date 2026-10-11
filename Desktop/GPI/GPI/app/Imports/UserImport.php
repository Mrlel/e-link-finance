<?php

namespace App\Imports;

use App\Models\Fonction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UserImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    // Variantes tolérées (accents/casse/espaces normalisés en amont) vers le slug canonique du rôle.
    private const ROLE_ALIASES = [
        'user' => 'user',
        'utilisateur' => 'user',
        'agent' => 'user',
        'chef_de_service' => 'chef_de_service',
        'chef_service' => 'chef_de_service',
        'sous_directeur' => 'sous_directeur',
        'gestionnaire_parc' => 'gestionnaire_parc',
        'gestionnaire_de_parc' => 'gestionnaire_parc',
        'admin' => 'admin',
        'administrateur' => 'admin',
        'technicien' => 'technicien',
        'directeur' => 'directeur',
        'ministre' => 'ministre',
        'point_focal' => 'point_focal',
        'service_gestionnaire' => 'service_gestionnaire',
    ];

    protected $direction_id;
    protected array $roleFallbacks = [];

    public function __construct($direction_id)
    {
        $this->direction_id = $direction_id;
    }

    // Lignes où le rôle saisi n'a pas été reconnu et a été remplacé par "user" - à afficher à l'admin.
    public function roleFallbacks(): array
    {
        return $this->roleFallbacks;
    }

    public function model(array $row)
    {
        // "fonction" est une entité normalisée (table fonctions, scopée par direction) : on
        // résout (ou crée) l'entrée correspondant au libellé de la cellule, puis on ne stocke
        // que son id sur l'utilisateur - le seul champ que la table users connaisse réellement.
        $fonction = Fonction::firstOrCreate([
            'direction_id' => $this->direction_id,
            'fonction' => trim($row['fonction']),
        ]);

        return new User([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'] ?? null,
            'matricule' => $row['matricule'],
            'email' => $row['email'],
            'contact' => $row['contact'],
            'emploie' => $row['emploie'],
            'fonction_id' => $fonction->id,
            'grade' => $row['grade'],
            'role' => $this->resolveRole($row),
            'direction_id' => $this->direction_id,
            'password' => Hash::make('12345678'), // Mot de passe par défaut, changement forcé à la première connexion
        ]);
    }

    // Tolère la casse, les accents et les espaces ("Technicien", "Chef de service", "Administrateur"…)
    // plutôt que de rejeter toute la ligne pour un simple écart de format ; retombe sur "user" (le
    // moins privilégié) si le rôle saisi n'est vraiment pas reconnu, et le signale via roleFallbacks().
    private function resolveRole(array $row): string
    {
        $raw = trim((string) ($row['role'] ?? ''));
        if ($raw === '') {
            return 'user';
        }

        $slug = self::ROLE_ALIASES[Str::slug($raw, '_')] ?? null;
        if ($slug !== null) {
            return $slug;
        }

        $this->roleFallbacks[] = "Rôle « {$raw} » non reconnu pour {$row['nom']} ({$row['matricule']}) - « user » appliqué par défaut.";
        return 'user';
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'matricule' => 'required|string|unique:users,matricule',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|string',
            'emploie' => 'required|string',
            'fonction' => 'required|string',
            'grade' => 'required|string',
            'role' => 'nullable|string',
        ];
    }
}
