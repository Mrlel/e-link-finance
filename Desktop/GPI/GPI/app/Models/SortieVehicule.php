<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortieVehicule extends Model
{
    use HasFactory;

    protected $table = 'sorties_vehicule';

    protected $fillable = [
        'vehicule_id', 'demandeur_id', 'valideur_id', 'direction_id',
        'type_sortie', 'motif', 'prestataire', 'lieu_destination',
        'date_sortie', 'date_retour_prevue', 'date_retour_effective',
        'retour_valide_par', 'statut', 'observations',
    ];

    protected $casts = [
        'date_sortie'           => 'date',
        'date_retour_prevue'    => 'date',
        'date_retour_effective' => 'date',
    ];

    public static array $typeLabels = [
        'maintenance_externe' => 'Maintenance externe',
        'reforme'             => 'Réforme',
        'enlevement'          => 'Enlèvement',
        'transfert'           => 'Transfert',
    ];

    public static array $statutLabels = [
        'en_cours'  => 'En cours',
        'retourne'  => 'Retourné',
        'definitif' => 'Définitif',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type_sortie] ?? $this->type_sortie;
    }

    public function getStatutLabelAttribute(): string
    {
        return self::$statutLabels[$this->statut] ?? $this->statut;
    }

    public function vehicule()        { return $this->belongsTo(Vehicule::class); }
    public function demandeur()       { return $this->belongsTo(User::class, 'demandeur_id'); }
    public function valideur()        { return $this->belongsTo(User::class, 'valideur_id'); }
    public function retourValidePar() { return $this->belongsTo(User::class, 'retour_valide_par'); }
    public function direction()       { return $this->belongsTo(Direction::class); }
}
