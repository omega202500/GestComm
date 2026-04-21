<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Créer ou récupérer le client
            if ($request->client_id) {
                $client = Client::find($request->client_id);
            } else {
                $client = Client::create([
                    'nom' => $request->client_nom,
                    'telephone' => $request->client_tel
                ]);
            }

            // Créer la vente
            $vente = Vente::create([
                'commercial_id' => Auth::id(),
                'client_id' => $client->id,
                'date_vente' => $request->date_vente ?? now(),
                'total_quantite' => $request->total_quantite,
                'montant_total' => $request->montant_total,
                'facture_ref' => $request->reference,
                'notes' => $request->notes
            ]);

            // Mettre à jour le stock
            foreach ($request->lignes as $ligne) {
                $produit = Produits::find($ligne['produit_id']);
                if ($produit) {
                    $produit->decrement('stock', $ligne['quantite']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vente enregistrée',
                'data' => $vente
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function statsJour()
    {
        $aujourdhui = now()->startOfDay();
        $stats = Vente::where('commercial_id', Auth::id())
            ->whereDate('date_vente', $aujourdhui)
            ->select(
                DB::raw('SUM(montant_total) as montant_total'),
                DB::raw('COUNT(DISTINCT client_id) as nb_clients'),
                DB::raw('COUNT(*) as nb_ventes')
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'montant_total' => $stats->montant_total ?? 0,
                'nb_clients' => $stats->nb_clients ?? 0,
                'nb_ventes' => $stats->nb_ventes ?? 0
            ]
        ]);
    }

    public function myVentes()
    {
        $ventes = Vente::where('commercial_id', Auth::id())
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $ventes]);
    }
}
