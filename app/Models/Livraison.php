<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    protected $fillable = [
        'commande_id',
        'terrain_id',
        'chauffeur_id',
        'date_livraison',
        'statut',
        'notes'
    ];

    protected $casts = [
        'date_livraison' => 'datetime',
    ];

    // Relation avec la commande
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Relation avec le terrain (qui est un User avec rôle 'terrain')
    public function terrain()
    {
        return $this->belongsTo(User::class, 'terrain_id');
    }

    // Relation avec le chauffeur (qui est un User avec rôle 'chauffeur')
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }
}
