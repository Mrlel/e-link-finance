<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrimoineDiversAssignment extends Model
{
    use HasFactory;

    protected $table = 'patrimoine_divers_assignments';

    protected $fillable = [
        'patrimoine_divers_id',
        'user_id',
        'assigned_by',
        'quantite',
        'assigned_at',
        'returned_at',
        'returned_by',
        'commentaire_retour',
        'direction_id',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function patrimoineDivers()
    {
        return $this->belongsTo(PatrimoineDivers::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
}
