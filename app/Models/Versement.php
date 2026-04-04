<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'commercial_terrain_id', 'montant', 
        'date_versement', 'mode_paiement', 'reference'
    ];

    protected $casts = [
        'date_versement' => 'date',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function commercialTerrain()
    {
        return $this->belongsTo(User::class, 'commercial_terrain_id');
    }
}