<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class password_changed extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'password', 'password_changed'
    ];
}
