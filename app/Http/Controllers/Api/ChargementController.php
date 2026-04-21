<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chargement;
use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChargementController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $chargement = Chargement::create([
                'commercial_id' => Auth::id(),
                'date_chargement' => $request->date_chargement ?? now(),
                'reference' => $request->reference,
                'fournisseur' => $request->fournisseur,
                'notes' => $request->notes,
                'statut' => 'en_attente'
            ]);

            foreach ($request->lignes as $ligne) {
                // Ajouter au chargement_items si vous avez une table
                // Mettre à jour le stock
                $produit = Produits::find($ligne['produit_id']);
                if ($produit) {
                    $produit->increment('stock', $ligne['quantite']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Chargement enregistré',
                'data' => $chargement
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function myChargements()
    {
        $chargements = Chargement::where('commercial_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $chargements]);
    }
}
