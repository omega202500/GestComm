<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $primaryKey = 'nom'; // 👈 Déclarer nom comme clé primaire
    public $incrementing = false;  // 👈 Désactiver l'auto-incrément
    protected $keyType = 'string';  // 👈 Type de clé string

    protected $fillable = ['nom', 'description', 'region'];

    public function users()
    {
        return $this->hasMany(User::class, 'nom_zone', 'nom');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'nom_zone', 'nom');
    }

    public function commandes()
    {
        return $this->hasManyThrough(
            Commande::class, 
            Client::class,
            'nom_zone', // Clé étrangère dans clients
            'client_id', // Clé étrangère dans commandes
            'nom',       // Clé primaire dans zones
            'id'         // Clé primaire dans clients
        );
    }
}