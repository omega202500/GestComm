<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Vente;
use App\Models\Versement;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats      = $this->buildStats();
        $activities = \App\Models\Activity::with('user')
                        ->orderBy('created_at', 'desc')
                        ->limit(20)->get();
        $newCount   = \App\Models\Activity::where('status', 'pending')->count();
        $periode    = $request->get('periode', 'mois');

        // Données pour la page (livraisons, terrains, chauffeurs, commandes)
        $livraisons = \App\Models\Livraison::with(['commande.client','terrain','chauffeur'])
                        ->orderBy('created_at','desc')->get()
                        ->map(fn($l) => [
                            'id'             => $l->id,
                            'commande_numero'=> $l->commande->numero ?? 'N/A',
                            'client_nom'     => $l->commande->client->nom ?? 'Inconnu',
                            'terrain_nom'    => $l->terrain->nom ?? 'N/A',
                            'chauffeur_nom'  => $l->chauffeur->nom ?? 'N/A',
                            'date_livraison' => $l->date_livraison,
                            'statut'         => $l->statut,
                            'commande_id'    => $l->commande_id,
                            'terrain_id'     => $l->terrain_id,
                            'chauffeur_id'   => $l->chauffeur_id,
                            'notes'          => $l->notes,
                        ]);

        $terrains   = User::where('role', 'terrain')->get(['id','nom']);
        $chauffeurs = User::where('role', 'chauffeur')->get(['id','nom']);
        $commandes  = Commande::with('client')->orderBy('created_at','desc')->get()
                        ->map(fn($c) => [
                            'id'     => $c->id,
                            'numero' => $c->numero ?? 'CMD-'.$c->id,
                            'client' => $c->client->nom ?? 'Client inconnu',
                            'statut' => $c->statut,
                        ]);
        $produits   = \App\Models\Produits::all();

        return view('dashboard', compact(
            'stats','activities','newCount','periode',
            'livraisons','terrains','chauffeurs','commandes','produits'
        ));
    }

    // Route /admin/dashboard/stats — appelée en AJAX par le dashboard
    public function stats(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $this->buildStats(),
        ]);
    }

    private function buildStats(): array
    {
        // Ventes
        $totalVentes  = 0;
        $nbVentes     = 0;
        $ventesAujourdhui = 0;

        if (class_exists(\App\Models\Vente::class) && \Schema::hasTable('ventes')) {
            $totalVentes      = \App\Models\Vente::sum('montant_total') ?? 0;
            $nbVentes         = \App\Models\Vente::count();
            $ventesAujourdhui = \App\Models\Vente::whereDate('created_at', today())->sum('montant_total') ?? 0;
        }

        // Versements
        $totalVersements   = 0;
        $versementsValides = 0;
        $versementsAttente = 0;

        if (class_exists(\App\Models\Versement::class) && \Schema::hasTable('versements')) {
            $totalVersements   = \App\Models\Versement::sum('montant') ?? 0;
            $versementsValides = \App\Models\Versement::where('statut', 'valide')->count();
            $versementsAttente = \App\Models\Versement::where('statut', 'en_attente')->count();
        }

        // Commandes
        $chiffreAffaires = Commande::where('statut', 'livree')->sum('montant_total') ?? 0;

        // Clients
        $totalClients = Client::count();
        $clientsAujourdhui = Client::whereDate('created_at', today())->count();

        // Performance commerciaux
        $performance = User::whereIn('role', ['terrain', 'chauffeur', 'commercial'])
            ->withCount(['commandes as total_commandes'])
            ->get()
            ->map(fn($u) => [
                'id'                    => $u->id,
                'nom'                   => $u->nom,
                'role'                  => $u->role,
                'total_commandes'       => $u->total_commandes,
                'total_ventes'          => 0, // à implémenter selon votre modèle
                'total_quantite_vendue' => 0,
                'objectif'              => 500000,
            ]);

        return [
            'chiffre_affaires'      => $chiffreAffaires,
            'total_ventes'          => $totalVentes,
            'nb_ventes'             => $nbVentes,
            'ventes_aujourd_hui'    => $ventesAujourdhui,
            'total_versements'      => $totalVersements,
            'versements_valides'    => $versementsValides,
            'versements_en_attente' => $versementsAttente,
            'total_clients'         => $totalClients,
            'clients_aujourd_hui'   => $clientsAujourdhui,
            'performance'           => $performance,
            'commandes'             => [
                'total'      => Commande::count(),
                'en_attente' => Commande::where('statut','en_attente')->count(),
                'en_cours'   => Commande::where('statut','en_cours')->count(),
                'livrees'    => Commande::where('statut','livree')->count(),
                'annulees'   => Commande::where('statut','annulee')->count(),
            ],
        ];
    }
}
