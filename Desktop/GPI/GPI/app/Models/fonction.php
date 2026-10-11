<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;

    protected $table = 'fonctions';

    protected $fillable = [
        'fonction',
        'direction_id',
    ];

   public function users()
    {
        return $this->hasMany(User::class);
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
}
