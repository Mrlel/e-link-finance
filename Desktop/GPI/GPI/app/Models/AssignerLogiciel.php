<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignerLogiciel extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipement_id',
        'licence_id',
        'logiciel_assigned_by',
        'direction_id',
        'assigned_at',
        'returned_at',
    ];
    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'equipement_id'); // ✅ Clé étrangère spécifiée
    }
    
    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
    
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'logiciel_assigned_by'); // ✅ Corrigé
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
}
