<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Rôles redirigés vers le tableau de bord admin après connexion.
     * Source unique de vérité : à mettre à jour ici uniquement si un nouveau rôle
     * "administratif" est ajouté (cf. LoginController, ChangePasswordController).
     */
    public const ADMIN_ROLES = [
        'admin',
        'chef_de_service',
        'sous_directeur',
        'gestionnaire_parc',
        'technicien',
        'directeur',
        'ministre',
        'point_focal',
        'service_gestionnaire',
    ];

    public function isAdminTier(): bool
    {
        return in_array($this->role, self::ADMIN_ROLES, true);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'matricule',
        'email',
        'password',
        'contact',
        'emploie',
        'grade',
        'role',
        'password_change_required', 
        'date_prise_service_un',
        'date_prise_service',
        'fonction_id',
        'direction_id',
        'equipement_id',
  ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_change_required' => 'boolean', 
    ];

   public function fonction()
{
    return $this->belongsTo(Fonction::class);
}

    public function demandesMaintenance()
    {
        return $this->hasMany(DemandeMaintenance::class);
    }
    
    public function direction()
    {
        return $this->belongsTo(\App\Models\Direction::class, 'direction_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
 /*public function attributions()
{
    return $this->hasMany(Attribution::class, 'user_id');
}*/
public function assignments()
{
    return $this->hasMany(Assignment::class);
}

// Si vous voulez accéder aux équipements attribués actuellement (par exemple, les derniers enregistrements sans date de retour)
public function equipementsActuels()
{
    return $this->belongsToMany(Equipement::class, 'assignments')
                ->withPivot('assigned_at', 'returned_at')
                ->wherePivot('returned_at', null);
}

    // Relation vers l'utilisateur qui reçoit l'équipement
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation vers l'utilisateur qui effectue l'attribution
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Relation avec le modèle Maintenance
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function directionAdministree()
    {
        return $this->hasOne(Direction::class, 'admin_id');
    }

    public function documents()
    {
        return $this->hasMany(Documents::class);
    }
    public function alertes()
    {
        return $this->hasMany(Alertes::class);
    }
    public function journal_modifs()
    {
        return $this->hasMany(Journal_modif::class);
    }
}
