<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'commercial_id',
        'client_id',
        'client_tel',
        'date_commande',
        'date_livraison',
        'statut',
        'total_quantite',
        'montant_total',
        'notes',
        'zone_id'
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'date_livraison' => 'datetime',
        'montant_total' => 'decimal:2',
        'total_quantite' => 'integer'
    ];

    // Relations
    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function livraisons()
    {
        return $this->hasMany(Livraison::class);
    }

    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeLivrees($query)
    {
        return $query->where('statut', 'livree');
    }

    // Accesseurs
    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' FCFA';
    }
}
