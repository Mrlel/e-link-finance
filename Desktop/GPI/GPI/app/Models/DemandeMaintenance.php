<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'statut_dmtc',
        'desc_prblem',
        'priorite_dmtc',
        'direction_id',
        'direction_traitante_id',
        'equipement_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenances()
    {
        return $this->belongsToMany(Maintenance::class, 'demande_maintenance_maintenance');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }
}

