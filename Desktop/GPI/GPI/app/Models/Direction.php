<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom_direction',
        'slug',
        'code_direction',
        'email_contact',
        'telephone',
        'logo',
        'adresse',
        'responsable',
        'statut',
        'type',
        'web_url',
    ];
    protected $casts = [
        'modules_autorises' => 'array',
    ];

    protected static function boot()
    {
    parent::boot();

        static::creating(function ($direction) {
            if (empty($direction->slug)) {
                $direction->slug = Str::slug($direction->nom_direction);
            }
        });
    }

    public function users()
{
    return $this->hasMany(User::class);
}

    public function fonctions()
{
    return $this->hasMany(fonction::class);
}
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    public function licences()
    {
        return $this->hasMany(Licence::class);
    }

    public function demandes_materiels()
    {
        return $this->hasMany(DemandeMateriel::class);
    }

    public function demandes_maintenance()
    {
        return $this->hasMany(DemandeMaintenance::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function source_acquisitions()
    {
        return $this->hasMany(SourceAcquisition::class);
    }

    public function assigner_logiciel()
    {
        return $this->hasMany(AssignerLogiciel::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
