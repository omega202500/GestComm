<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produits;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produits::all();
        return response()->json([
            'success' => true,
            'data' => $produits
        ]);
    }

    public function show($id)
    {
        $produit = Produits::findOrFail($id);
        return response()->json(['success' => true, 'data' => $produit]);
    }

    public function stockAlerte()
    {
        $produits = Produits::where('stock', '<=', 10)->get();
        return response()->json(['success' => true, 'data' => $produits]);
    }
}
