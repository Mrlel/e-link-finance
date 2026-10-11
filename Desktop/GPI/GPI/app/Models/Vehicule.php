<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $table = 'vehicules';

    protected $fillable = [
        'immatriculation',
        'categorie',
        'marque',
        'modele',
        'lieu_utilisation',
        'numero_chassis',
        'couleur',
        'etat',
        'date_mec',
        'mode_acquisition',
        'statut',
        'direction_id',
        'created_by',
    ];

    protected $casts = [
        'date_mec' => 'date',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(VehiculeAssignment::class);
    }

    public function affectationActive()
    {
        return $this->hasOne(VehiculeAssignment::class)->whereNull('returned_at');
    }

    public function utilisateurActuel()
    {
        return $this->belongsToMany(User::class, 'vehicule_assignments')
                    ->withPivot('assigned_at', 'returned_at')
                    ->wherePivot('returned_at', null);
    }

    public function sorties()
    {
        return $this->hasMany(SortieVehicule::class);
    }

    public function sortieActive()
    {
        return $this->hasOne(SortieVehicule::class)->where('statut', 'en_cours');
    }
}
