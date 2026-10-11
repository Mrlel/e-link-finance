<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilierAssignment extends Model
{
    use HasFactory;

    protected $table = 'mobilier_assignments';

    protected $fillable = [
        'mobilier_id', 'user_id', 'assigned_by',
        'assigned_at', 'returned_at', 'returned_by',
        'motif_affectation', 'commentaire_retour', 'direction_id',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function mobilier()    { return $this->belongsTo(Mobilier::class); }
    public function user()        { return $this->belongsTo(User::class, 'user_id'); }
    public function assignedBy()  { return $this->belongsTo(User::class, 'assigned_by'); }
    public function returnedBy()  { return $this->belongsTo(User::class, 'returned_by'); }
    public function direction()   { return $this->belongsTo(Direction::class); }
}
