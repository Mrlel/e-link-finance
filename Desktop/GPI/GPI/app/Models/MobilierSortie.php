<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilierSortie extends Model
{
    use HasFactory;

    protected $table = 'mobilier_sorties';

    protected $fillable = [
        'mobilier_id', 'demandeur_id', 'direction_id',
        'type_sortie', 'motif', 'date_sortie', 'observations',
    ];

    protected $casts = ['date_sortie' => 'date'];

    public static array $typeLabels = [
        'reforme'   => 'Réforme',
        'enlevement'   => 'Enlèvement',
        'transfert' => 'Transfert',
        'perte'     => 'Perte / Vol',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type_sortie] ?? $this->type_sortie;
    }

    public function mobilier()  { return $this->belongsTo(Mobilier::class); }
    public function demandeur() { return $this->belongsTo(User::class, 'demandeur_id'); }
    public function direction() { return $this->belongsTo(Direction::class); }
}
