<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    // Créer une commande (pour les commerciaux terrain)
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
            'date_livraison' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Créer ou récupérer le client
            $client = Client::firstOrCreate(
                ['telephone' => $request->client_tel],
                ['nom' => $request->client_nom]
            );

            // Créer la commande
            $commande = Commande::create([
                'commercial_id' => Auth::id(),
                'client_id' => $client->id,
                'client_tel' => $request->client_tel,
                'date_commande' => now(),
                'date_livraison' => $request->date_livraison ?? now()->addDays(3),
                'statut' => 'en_attente',
                'total_quantite' => $request->total_quantite,
                'montant_total' => $request->montant_total,
                'notes' => $request->notes,
                'zone_id' => Auth::user()->zone_id
            ]);

            // Enregistrer l'activité
            Activity::create([
                'user_id' => Auth::id(),
                'type' => 'commande',
                'reference' => $commande->id,
                'status' => 'en_attente',
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'commande' => $commande
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
        $count = Commande::count();
        return response()->json(['count' => $count]);
    }

    // Liste des commandes (pour admin)
    public function index(Request $request)
    {
        $query = Commande::with(['commercial', 'client', 'zone']);

        // Filtres
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }
        if ($request->date_debut) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($commandes);
    }

    // Détail d'une commande
    public function show($id)
    {
        $commande = Commande::with(['commercial', 'client', 'zone', 'livraisons'])->findOrFail($id);
        return response()->json($commande);
    }

    // Mettre à jour le statut (admin)
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,livree,annulee'
        ]);

        $commande = Commande::findOrFail($id);
        $commande->update(['statut' => $request->statut]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'statut' => $commande->statut
        ]);
    }

    // Commandes du commercial connecté
    public function myCommandes()
    {
        $commandes = Commande::where('commercial_id', Auth::id())
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($commandes);
    }

    // ======================
    // MÉTHODES SUPPLÉMENTAIRES POUR ADMIN
    // ======================

    // Récupérer les items d'une commande (si vous avez une table commande_items)
    public function getItems($id)
    {
        $commande = Commande::findOrFail($id);

        // Si vous avez une relation 'items' dans votre modèle Commande
        // $items = $commande->items;

        // Version temporaire
        $items = [];

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    // Statistiques du dashboard admin
    public function getDashboardStats()
    {
        $stats = [
            'total_commandes' => Commande::count(),
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'commandes_en_cours' => Commande::where('statut', 'en_cours')->count(),
            'commandes_livrees' => Commande::where('statut', 'livree')->count(),
            'commandes_annulees' => Commande::where('statut', 'annulee')->count(),
            'chiffre_affaires' => Commande::where('statut', 'livree')->sum('montant_total'),
            'chiffre_affaires_total' => Commande::sum('montant_total')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
