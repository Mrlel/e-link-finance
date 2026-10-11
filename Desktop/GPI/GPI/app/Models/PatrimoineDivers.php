<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrimoineDivers extends Model
{
    use HasFactory;

    protected $table = 'patrimoine_divers';

    public const COULEURS = [
        'Noir', 'Cyan', 'Magenta', 'Jaune', 'Blanc', 'Rouge', 'Bleu', 'Vert', 'Gris', 'Autre',
    ];

    protected $fillable = [
        'libelle',
        'nombre',
        'categorie',
        'couleur',
        'date_acquisition',
        'etat',
        'statut',
        'user_id',
        'direction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

    public function assignments()
    {
        return $this->hasMany(PatrimoineDiversAssignment::class);
    }

    public function utilisateursActuels()
    {
        return $this->belongsToMany(User::class, 'patrimoine_divers_assignments')
                    ->withPivot('quantite', 'assigned_at', 'returned_at')
                    ->wherePivot('returned_at', null);
    }
}
