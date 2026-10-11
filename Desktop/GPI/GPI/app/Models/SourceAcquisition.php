<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceAcquisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_source_acquisition',
        'email_source_acquisition',
        'contact_source_acquisition',
        'type_source_acquisition',
        'description_source_acquisition',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }
    public function contrat_garanties()
    {
        return $this->hasMany(Contrat_garantie::class);
    }
}
