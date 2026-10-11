<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alertes extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_alerte',
        'date_prevue',
        'vu_alerte',
        'equipement_id',
        'user_id',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
