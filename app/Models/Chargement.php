<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chargement extends Model
{
    use HasFactory;

    protected $fillable = [
        'chauffeur_id', 'produit_id', 'quantite', 'date_chargement', 'statut'
    ];

    protected $casts = [
        'date_chargement' => 'date',
    ];

    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}


