<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobilier extends Model
{
    use HasFactory;

    protected $table = 'mobiliers';

    protected $fillable = [
        'designation', 'marque', 'reference', 'num_inventaire',
        'date_acquisition', 'date_fin_vie', 'alerte_fin_vie_envoyee',
        'etat', 'statut', 'mode_acquisition', 'observations',
        'direction_id', 'created_by',
    ];

    protected $casts = [
        'date_acquisition'       => 'date',
        'date_fin_vie'           => 'date',
        'alerte_fin_vie_envoyee' => 'boolean',
    ];


    public function direction()   { return $this->belongsTo(Direction::class); }
    public function createdBy()   { return $this->belongsTo(User::class, 'created_by'); }

    public function assignments()
    {
        return $this->hasMany(MobilierAssignment::class);
    }

    public function affectationActive()
    {
        return $this->hasOne(MobilierAssignment::class)->whereNull('returned_at');
    }

    public function sorties()
    {
        return $this->hasMany(MobilierSortie::class);
    }
}
