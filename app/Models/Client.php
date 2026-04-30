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
        'solde'

    ];

    // Pas besoin de redéfinir UPDATED_AT et CREATED_AT

    protected $casts = [
        'solde' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
