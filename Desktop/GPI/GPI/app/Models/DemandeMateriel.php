<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DemandeMateriel extends Model
{
    use HasFactory;

    protected $table = 'demande_materiels';

    protected $fillable = [
        'user_id',  
        'typ_mat', 
        'priorite_dem',
        'desc_demande', 
        'statut_dem',   
        'demande_autorisation',
        'commentaire',
        'direction_id',
    ];

    // Relation "DemandeMateriel appartient à un utilisateur"
public function user()
{
    return $this->belongsTo(User::class); // Relation avec le modèle User
}

public function direction()
{
    return $this->belongsTo(Direction::class);
}
}
