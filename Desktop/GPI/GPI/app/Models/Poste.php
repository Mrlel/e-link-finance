<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;
      protected $fillable = [
        'code_poste',
        'emplacement',
        'qr_code',
        'user_id',
        'direction_id',
    ];


  /*  public function getEquipementsAttribute()
{
    return $this->equipements()->with('user')->get()->groupBy('categorie');
}*/
    // Un poste possède plusieurs équipements
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }
    public function direction()
    {
        return $this->belongsTo(\App\Models\Direction::class);
    }
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
