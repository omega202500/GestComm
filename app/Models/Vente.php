<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    /**
     * Le nom de la table (optionnel si c'est le pluriel du modèle)
     */
    protected $table = 'ventes';

    /**
     * Colonnes remplissables
     */
    protected $fillable = [
        'commercial_id',
        'produit_id', 
        'client_id',
        'total_quantite',
        'montant_total',
        'facture_ref',
        'prix_unitaire',
        'date_vente'
    ];

    /**
     * Casts des attributs
     */
    protected $casts = [
        'montant_total' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'date_vente' => 'datetime',
        'total_quantite' => 'integer'
    ];

    /**
     * Valeurs par défaut des attributs
     */
    protected $attributes = [
        'total_quantite' => 0,
        'montant_total' => 0.00,
        'prix_unitaire' => 0.00
    ];

    /**
     * Relations
     */

    // Commercial qui a effectué la vente
    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    // Produit vendu
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    // Client acheteur
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Définir automatiquement la date de vente à la création
        static::creating(function ($vente) {
            if (empty($vente->date_vente)) {
                $vente->date_vente = now();
            }
            
            // Calculer automatiquement le montant total si vide
            if (empty($vente->montant_total) && $vente->total_quantite && $vente->prix_unitaire) {
                $vente->montant_total = $vente->calculateMontantTotal();
            }
        });

        // Mettre à jour le montant total avant de sauvegarder
        static::saving(function ($vente) {
            if ($vente->isDirty(['total_quantite', 'prix_unitaire'])) {
                $vente->montant_total = $vente->calculateMontantTotal();
            }
        });
    }

    /**
     * Méthode pour calculer le montant total
     */
    public function calculateMontantTotal()
    {
        return $this->total_quantite * $this->prix_unitaire;
    }

    /**
     * Scope pour filtrer par commercial
     */
    public function scopeByCommercial($query, $commercialId)
    {
        return $query->where('commercial_id', $commercialId);
    }

    /**
     * Scope pour filtrer par produit
     */
    public function scopeByProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    /**
     * Scope pour filtrer par client
     */
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_vente', [$startDate, $endDate]);
    }

    /**
     * Scope pour les ventes aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date_vente', today());
    }

    /**
     * Scope pour les ventes de ce mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date_vente', now()->month)
                     ->whereYear('date_vente', now()->year);
    }

    /**
     * Accessor pour le montant total formaté
     */
    public function getMontantTotalFormattedAttribute()
    {
        return number_format($this->montant_total, 2, ',', ' ') . ' FCFA';
    }

    /**
     * Accessor pour le prix unitaire formaté
     */
    public function getPrixUnitaireFormattedAttribute()
    {
        return number_format($this->prix_unitaire, 2, ',', ' ') . ' FCFA';
    }

    /**
     * Accessor pour la date formatée
     */
    public function getDateVenteFormattedAttribute()
    {
        return $this->date_vente ? $this->date_vente->format('d/m/Y H:i') : null;
    }

    /**
     * Accessor pour la date seulement (sans l'heure)
     */
    public function getDateOnlyAttribute()
    {
        return $this->date_vente ? $this->date_vente->format('d/m/Y') : null;
    }

    /**
     * Mutator pour le prix unitaire
     */
    public function setPrixUnitaireAttribute($value)
    {
        $this->attributes['prix_unitaire'] = is_numeric($value) ? $value : 0;
    }

    /**
     * Mutator pour la quantité totale
     */
    public function setTotalQuantiteAttribute($value)
    {
        $this->attributes['total_quantite'] = is_numeric($value) ? max(0, intval($value)) : 0;
    }

    /**
     * Obtenir le bénéfice si vous avez un prix d'achat dans Produit
     */
    public function getBeneficeAttribute()
    {
        if ($this->produit && $this->produit->prix_achat) {
            return ($this->prix_unitaire - $this->produit->prix_achat) * $this->total_quantite;
        }
        return 0;
    }

    /**
     * Accessor pour le bénéfice formaté
     */
    public function getBeneficeFormattedAttribute()
    {
        return number_format($this->benefice, 2, ',', ' ') . ' FCFA';
    }

    /**
     * Générer une référence de facture automatique
     */
    public function generateFactureRef()
    {
        if (empty($this->facture_ref)) {
            $date = now()->format('Ymd');
            $count = self::whereDate('date_vente', today())->count() + 1;
            $this->facture_ref = 'FACT-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        }
        return $this->facture_ref;
    }

    /**
     * Vérifier si la vente a une référence de facture
     */
    public function hasFactureRef()
    {
        return !empty($this->facture_ref);
    }
}