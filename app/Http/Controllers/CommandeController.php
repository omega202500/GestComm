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
    // ============================
    // LISTE DES COMMANDES
    // Répond JSON si requête AJAX/API, sinon vue HTML
    // ============================
    public function index(Request $request)
    {
        $query = Commande::with(['client', 'commercial']);

        // Filtres optionnels
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }
        if ($request->date_debut) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }

        // Si requête AJAX ou Accept: application/json → retourner JSON
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Retourner un tableau direct (pas paginé) pour éviter le bug .filter()
            $commandes = $query->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data'    => $commandes,
                'total'   => $commandes->count(),
            ]);
        }

        // Sinon retourner la vue HTML
        $commandes = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('commandes.index', compact('commandes'));
    }

    // ============================
    // CRÉER UNE COMMANDE (commerciaux terrain)
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'client_nom'                  => 'required|string|max:150',
            'client_tel'                  => 'required|string|max:20',
            'produits'                    => 'required|array|min:1',
            'produits.*.produit_id'       => 'required|exists:produits,id',
            'produits.*.quantite'         => 'required|integer|min:1',
            'produits.*.prix_unitaire'    => 'required|numeric|min:0',
            'montant_total'               => 'required|numeric|min:0',
            'total_quantite'              => 'required|integer|min:1',
            'date_livraison'              => 'nullable|date',
            'notes'                       => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Créer ou récupérer le client
            $client = Client::firstOrCreate(
                ['telephone' => $request->client_tel],
                ['nom'       => $request->client_nom]
            );

            // Créer la commande
            $commande = Commande::create([
                'commercial_id'  => Auth::id(),
                'client_id'      => $client->id,
                'client_tel'     => $request->client_tel,
                'date_commande'  => now(),
                'date_livraison' => $request->date_livraison ?? now()->addDays(3),
                'statut'         => 'en_attente',
                'total_quantite' => $request->total_quantite,
                'montant_total'  => $request->montant_total,
                'notes'          => $request->notes,
                'zone_id'        => Auth::user()->zone_id ?? null,
            ]);

            // Enregistrer l'activité
            Activity::create([
                'user_id'    => Auth::id(),
                'type'       => 'commande',
                'reference'  => $commande->id,
                'status'     => 'en_attente',
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Commande créée avec succès',
                'commande' => $commande,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ============================
    // COMPTER LES COMMANDES
    // Doit être déclaré AVANT Route::resource() dans web.php
    // ============================
    public function count()
    {
        return response()->json([
            'success' => true,
            'count'   => Commande::count(),
        ]);
    }

    // ============================
    // DÉTAIL D'UNE COMMANDE
    // ============================
    public function show($id)
    {
        $commande = Commande::with(['commercial', 'client', 'zone', 'livraisons'])->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'data' => $commande]);
        }

        return view('commandes.show', compact('commande'));
    }

    // ============================
    // METTRE À JOUR LE STATUT (admin)
    // ============================
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,livree,annulee',
        ]);

        $commande = Commande::findOrFail($id);
        $commande->update(['statut' => $request->statut]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'statut'  => $commande->statut,
        ]);
    }

    // ============================
    // COMMANDES DU COMMERCIAL CONNECTÉ
    // ============================
    public function myCommandes()
    {
        $commandes = Commande::where('commercial_id', Auth::id())
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $commandes,
        ]);
    }

    // ============================
    // STATISTIQUES DASHBOARD ADMIN
    // ============================
    public function getDashboardStats()
    {
        $stats = [
            'total_commandes'       => Commande::count(),
            'commandes_en_attente'  => Commande::where('statut', 'en_attente')->count(),
            'commandes_en_cours'    => Commande::where('statut', 'en_cours')->count(),
            'commandes_livrees'     => Commande::where('statut', 'livree')->count(),
            'commandes_annulees'    => Commande::where('statut', 'annulee')->count(),
            'chiffre_affaires'      => Commande::where('statut', 'livree')->sum('montant_total'),
            'chiffre_affaires_total'=> Commande::sum('montant_total'),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }

    // ============================
    // ITEMS D'UNE COMMANDE
    // ============================
    public function getItems($id)
    {
        $commande = Commande::findOrFail($id);
        $items = $commande->items ?? [];

        return response()->json(['success' => true, 'data' => $items]);
    }
}
