<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
         'user_id',
        'equipement_id',
        'assigned_by',
        'assigned_at',
        'confirmed',
        'returned_at',
        'returned_by',
        'commentaire_retour',
        'etat_retour',
        'direction_id'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'returned_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Utilisateur qui reçoit l'équipement
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Utilisateur qui attribue l'équipement
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Équipement attribué
    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'equipement_id');
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
}
