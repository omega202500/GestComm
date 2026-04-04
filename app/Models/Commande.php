<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    // Le nom de la table (optionnel si c'est le pluriel du modèle)
    protected $table = 'commandes';

    // Colonnes remplissables
    protected $fillable = [
        'commercial_id', 
        'client_id', 
        'zone_id', 
        'statut', 
        'client_tel',
        'total_quantite',
        'montant_total',
        'notes',
        'date_livraison',
        'date_commande'  // Ajouté selon la table
    ];

    // Casts des attributs
    protected $casts = [
        'date_commande' => 'datetime',
        'date_livraison' => 'datetime',
        'montant_total' => 'decimal:2',
        'total_quantite' => 'integer'
    ];

    // Valeurs par défaut des attributs
    protected $attributes = [
        'statut' => 'en_attente',
        'total_quantite' => 0,
        'montant_total' => 0.00
    ];

    /**
     * Relations
     */

    // Commercial qui a pris la commande
    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    // Client (si vous avez une table clients)
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Zone de livraison
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    // Items de la commande
    public function items()
    {
        return $this->hasMany(CommandeItem::class, 'commande_id');
    }

    // Ventes liées à cette commande
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'commande_id');
    }

    // Versements pour cette commande
    public function versements()
    {
        return $this->hasMany(Versement::class, 'commande_id');
    }

    /**
     * Statuts possibles
     */
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_LIVREE = 'livree';
    const STATUT_ANNULEE = 'annulee';
    // Vous pouvez ajouter d'autres statuts si nécessaire, par exemple :
    // const STATUT_EN_COURS = 'en_cours';
    // const STATUT_PARTIELLEMENT_LIVREE = 'partiellement_livree';

    /**
     * Liste des statuts avec leurs libellés
     */
    public static function getStatuts()
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_LIVREE => 'Livrée',
            self::STATUT_ANNULEE => 'Annulée',
        ];
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les commandes en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE);
    }

    /**
     * Scope pour les commandes livrées
     */
    public function scopeLivrees($query)
    {
        return $query->where('statut', self::STATUT_LIVREE);
    }

    /**
     * Scope pour les commandes annulées
     */
    public function scopeAnnulees($query)
    {
        return $query->where('statut', self::STATUT_ANNULEE);
    }

    /**
     * Mutator pour formater le numéro de téléphone
     */
    public function setClientTelAttribute($value)
    {
        // Nettoyer le numéro de téléphone (enlever les espaces, tirets, etc.)
        $this->attributes['client_tel'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Accessor pour le numéro de téléphone formaté
     */
    public function getClientTelFormattedAttribute()
    {
        $tel = $this->client_tel;
        if (strlen($tel) == 10) {
            return substr($tel, 0, 2) . ' ' . substr($tel, 2, 2) . ' ' . 
                   substr($tel, 4, 2) . ' ' . substr($tel, 6, 2) . ' ' . 
                   substr($tel, 8, 2);
        }
        return $tel;
    }

    /**
     * Accessor pour le statut formaté
     */
    public function getStatutFormattedAttribute()
    {
        $statuts = self::getStatuts();
        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Accessor pour le montant total formaté
     */
    public function getMontantTotalFormattedAttribute()
    {
        return number_format($this->montant_total, 2, ',', ' ') . ' FCFA';
    }

    /**
     * Vérifier si la commande est en attente
     */
    public function isEnAttente()
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    /**
     * Vérifier si la commande est livrée
     */
    public function isLivree()
    {
        return $this->statut === self::STATUT_LIVREE;
    }

    /**
     * Vérifier si la commande est annulée
     */
    public function isAnnulee()
    {
        return $this->statut === self::STATUT_ANNULEE;
    }

    /**
     * Calculer le montant restant à payer
     * (Supposant que vous avez une relation versements)
     */
    public function getMontantRestantAttribute()
    {
        $totalVersements = $this->versements()->sum('montant');
        return max(0, $this->montant_total - $totalVersements);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Définir la date_commande automatiquement à la création
        static::creating(function ($commande) {
            if (empty($commande->date_commande)) {
                $commande->date_commande = now();
            }
        });
    }
}