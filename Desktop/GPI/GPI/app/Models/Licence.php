<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation_licence',
        'type_licence',
        'date_expiration',
        'cle_licence',
        'environnement',
        'langage_version',
        'sgbd_version',
        'version',
        'base_donnees',
        'fichier_licence',
        'fichier_app',
        'libelle_licence',
        'direction_id',
    ];

    // Relation avec Equipement (Many to One)
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }
    public function equipementsAssignes()
{
    return $this->hasMany(AssignerLogiciel::class);
}
    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
}
