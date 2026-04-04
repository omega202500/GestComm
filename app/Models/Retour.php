<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'produit_id', 'quantite', 'raison', 'date_retour'
    ];

    protected $casts = [
        'date_retour' => 'date',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function livraison(){ return $this->belongsTo(Livraison::class); 
    }

}
