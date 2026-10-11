<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat_garantie extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'description',
        'reference_contrat',
        'date_debut',
        'date_fin',
        'equipement_id',
        'source_acquisition_id',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function source_acquisition()
    {
        return $this->belongsTo(SourceAcquisition::class);
    }
}
