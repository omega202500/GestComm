<?php

namespace App\Services;

use App\Models\Vente;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class VenteService
{
    public function getVentesFiltrees($filtres = [])
    {
        $query = Vente::with(['produit', 'client', 'commercial'])
            ->orderBy('date_vente', 'desc');

        if (!empty($filtres['commercial_id'])) {
            $query->where('commercial_id', $filtres['commercial_id']);
        }

        if (!empty($filtres['produit_id'])) {
            $query->where('produit_id', $filtres['produit_id']);
        }

        if (!empty($filtres['date_debut']) && !empty($filtres['date_fin'])) {
            $query->whereBetween('date_vente', [$filtres['date_debut'], $filtres['date_fin']]);
        }

        return $query->paginate(20);
    }

    public function creerVente(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Vérifier le stock
            $produit = Produit::findOrFail($data['produit_id']);
            
            if ($produit->stock < $data['total_quantite']) {
                throw new \Exception('Stock insuffisant');
            }

            // Créer la vente
            $vente = Vente::create($data);

            // Mettre à jour le stock
            $produit->decrement('stock', $data['total_quantite']);

            return $vente;
        });
    }

    public function getStatistiquesVentes($dateDebut = null, $dateFin = null)
    {
        $query = Vente::select(
            DB::raw('SUM(montant_total) as total_ventes'),
            DB::raw('SUM(total_quantite) as total_quantite'),
            DB::raw('COUNT(*) as nombre_ventes'),
            DB::raw('AVG(prix_unitaire) as prix_moyen')
        );

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
        }

        return $query->first();
    }

    public function getVentesParProduit($dateDebut = null, $dateFin = null)
    {
        $query = Vente::select(
            'produit_id',
            DB::raw('SUM(total_quantite) as quantite_vendue'),
            DB::raw('SUM(montant_total) as chiffre_affaires')
        )->with('produit');

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
        }

        return $query->groupBy('produit_id')->orderBy('quantite_vendue', 'desc')->get();
    }

    public function getVentesParCommercial($dateDebut = null, $dateFin = null)
    {
        $query = Vente::select(
            'commercial_id',
            DB::raw('SUM(montant_total) as total_ventes'),
            DB::raw('COUNT(*) as nombre_ventes'),
            DB::raw('AVG(prix_unitaire) as prix_moyen')
        )->with('commercial');

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
        }

        return $query->groupBy('commercial_id')->orderBy('total_ventes', 'desc')->get();
    }
}