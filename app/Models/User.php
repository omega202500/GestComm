<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'password',
        'role',
        'statut',
        'photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'statut' => 'boolean',
    ];

    // Mutateur pour hasher le mot de passe automatiquement
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // ======================
    // RELATIONS
    // ======================
    
    /**
     * Relation avec les commandes
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'commercial_id');
    }
    
    /**
     * Relation avec les ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'commercial_id');
    }
    
    /**
     * Relation avec les versements
     */
    public function versements()
    {
        return $this->hasMany(Versement::class, 'commercial_id');
    }
    
    /**
     * Relation avec la zone
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
