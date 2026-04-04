<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'telephone',
        'zone_id',
        'adresse',
        'email',
        'solde'
    ];

    // Si votre table utilise 'UPDATE_at' au lieu de 'updated_at'
    const UPDATED_AT = 'UPDATE_at';
    const CREATED_AT = 'created_at';

    protected $casts = [
        'solde' => 'decimal:2',
        'created_at' => 'datetime',
        'UPDATE_at' => 'datetime'
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}