<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'date_demande',
        'date_realisation',
        'type_maintenance',
        'statut_maintenance',
        'demande_maintenance_id',
        'direction_id',
        'direction_traitante_id'
    ];

    protected $casts = [
        'date_demande' => 'datetime',
        'date_realisation' => 'datetime',
    ];

    // Relation avec le modèle DemandeMaintenance
    public function demandes()
    {
        return $this->belongsToMany(DemandeMaintenance::class, 'demande_maintenance_maintenance');
    }

    // Relation avec le modèle User (demandeur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le modèle Equipement
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    // Relation avec le modèle Direction
    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

    // Relation avec la demande de maintenance liée
    public function demandeMaintenance()
    {
        return $this->belongsTo(DemandeMaintenance::class, 'demande_maintenance_id');
    }

}
