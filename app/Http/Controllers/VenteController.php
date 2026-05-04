<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    // Créer une vente (pour les chauffeurs)
    public function store(Request $request)
    {
        $request->validate([
            'client_nom' => 'required|string|max:150',
            'client_tel' => 'required|string|max:20',
            'produits' => 'required|array',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
            'montant_total' => 'required|numeric|min:0',
            'total_quantite' => 'required|integer|min:1',
            'mode_paiement' => 'required|in:espece,mobile_money,cheque,credit',
            'montant_paye' => 'required|numeric|min:0',
            'facture_ref' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Créer ou récupérer le client
            $client = Client::firstOrCreate(
                ['telephone' => $request->client_tel],
                ['nom' => $request->client_nom]
            );

            // Créer la vente
            $vente = Vente::create([
                'commercial_id' => Auth::id(),
                'client_id' => $client->id,
                'date_vente' => now(),
                'total_quantite' => $request->total_quantite,
                'montant_total' => $request->montant_total,
                'facture_ref' => $request->facture_ref ?? 'VENTE-' . time()
            ]);

            // Créer les lignes de vente
            foreach ($request->produits as $produit) {
                $vente->details()->create([
                    'produit_id' => $produit['produit_id'],
                    'quantite' => $produit['quantite'],
                    'prix_unitaire' => $produit['prix_unitaire'],
                    'total' => $produit['quantite'] * $produit['prix_unitaire']
                ]);

                // Mettre à jour le stock
                $produitModel = \App\Models\Produits::find($produit['produit_id']);
                $produitModel->decrement('stock', $produit['quantite']);
            }

            // Enregistrer l'activité
            Activity::create([
                'user_id' => Auth::id(),
                'type' => 'vente',
                'reference' => $vente->id,
                'status' => 'en_attente',
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vente enregistrée avec succès',
                'vente' => $vente
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

   public function count()
{
    return response()->json([
        'success' => true,
        'count'   => \App\Models\Vente::count(),
    ]);
}

public function stats()
{
    return response()->json([
        'success'    => true,
        'total'      => \App\Models\Vente::sum('montant_total'),
        'count'      => \App\Models\Vente::count(),
        'aujourd_hui'=> \App\Models\Vente::whereDate('created_at', today())->sum('montant_total'),
    ]);
}

    // Liste des ventes (admin)
    public function index(Request $request)
    {
        $query = Vente::with(['commercial', 'client', 'produits']);

        if ($request->date) {
            $query->whereDate('date_vente', $request->date);
        }

        $ventes = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($ventes);
    }

    // Ventes du commercial connecté
    public function myVentes()
    {
        $ventes = Vente::where('commercial_id', Auth::id())
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($ventes);
    }

    // Statistiques du jour pour le chauffeur
    public function statsJour()
    {
        $aujourdhui = now()->startOfDay();

        $stats = [
            'total_ventes' => Vente::where('commercial_id', Auth::id())
                ->whereDate('date_vente', $aujourdhui)
                ->sum('montant_total'),
            'nb_ventes' => Vente::where('commercial_id', Auth::id())
                ->whereDate('date_vente', $aujourdhui)
                ->count(),
            'nb_clients' => Vente::where('commercial_id', Auth::id())
                ->whereDate('date_vente', $aujourdhui)
                ->distinct('client_id')
                ->count('client_id')
        ];

        return response()->json($stats);
    }
}
