<?php

namespace App\Services;

use App\Models\Produits;
use Illuminate\Support\Facades\DB;

class ProduitService
{
    public function getProduitsAvecStock()
    {
        return Produits::withSum('stock as stock_total', 'quantite')
            ->orderBy('nom')
            ->get();
    }

    public function creerProduit(array $data)
    {
        return Produits::create($data);
    }

    public function mettreAJourStock($produitId, $quantite)
    {
        return Produits::where('id', $produitId)
            ->update(['stock' => DB::raw("stock + $quantite")]);
    }

    public function getRetoursProduits($dateDebut = null, $dateFin = null)
    {


    }

}
