<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $table = 'ventes';

    protected $fillable = [
        'commercial_id',
        'produit_id',
        'client_id',
        'date_vente',
        'total_quantite',
        'montant_total',
        'facture_ref',
        'prix_unitaire'
    ];

    protected $casts = [
        'date_vente' => 'datetime',
        'montant_total' => 'decimal:2',
        'prix_unitaire' => 'decimal:2'
    ];

    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produits::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' FCFA';
    }
}
