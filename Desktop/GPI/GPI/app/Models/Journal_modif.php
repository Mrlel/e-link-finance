<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal_modif extends Model
{
    use HasFactory;
    protected $fillable = [
        'action',
        'description',
        'equipement_id',
        'vehicule_id',
        'mobilier_id',
        'patrimoine_divers_id',
        'licence_id',
        'poste_id',
        'target_user_id',
        'direction_id',
        'user_id',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function mobilier()
    {
        return $this->belongsTo(Mobilier::class);
    }

    public function patrimoineDivers()
    {
        return $this->belongsTo(PatrimoineDivers::class);
    }

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }

    // L'utilisateur concerné par l'action (ex: le compte créé/modifié/supprimé) -
    // distinct de user() qui est l'acteur ayant réalisé l'action.
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
