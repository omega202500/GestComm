<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\ActivityService;
use App\Models\Livraison;
use App\Models\User;  // ← Utiliser User au lieu de Terrain et Chauffeur
use App\Models\Commande;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $activityService;

    public function __construct(
        DashboardService $dashboardService,
        ActivityService $activityService
    ) {
        $this->dashboardService = $dashboardService;
        $this->activityService = $activityService;
    }

    public function index(Request $request)
    {
        $periode = $request->get('periode', 'mois');
        $stats = $this->dashboardService->getDashboardStats($periode);
        $newCount = $this->dashboardService->countNewActivities();
        $activities = $this->dashboardService->getRecentActivities(10);

        // Récupérer les livraisons depuis la base de données
        $livraisons = Livraison::with(['commande.client', 'chauffeur', 'terrain'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($livraison) {
                return [
                    'id' => $livraison->id,
                    'commande_id' => $livraison->commande->id ?? 'N/A',
                    'client_nom' => $livraison->commande->client->nom ?? 'Client inconnu',
                    'terrain_nom' => $livraison->terrain->nom ?? 'Terrain inconnu',  // terrain est un User avec rôle 'terrain'
                    'chauffeur_nom' => $livraison->chauffeur->nom ?? 'Chauffeur inconnu', // chauffeur est un User avec rôle 'chauffeur'
                    'date_livraison' => $livraison->date_livraison,
                    'statut' => $livraison->statut,
                    'notes' => $livraison->notes
                ];
            });

        // Récupérer les terrains (utilisateurs avec rôle 'terrain')
        $terrains = User::where('role', 'terrain')
            ->where('statut', true)
            ->select('id', 'nom')
            ->orderBy('nom')
            ->get();

        // Récupérer les chauffeurs (utilisateurs avec rôle 'chauffeur')
        $chauffeurs = User::where('role', 'chauffeur')
            ->where('statut', true)
            ->select('id', 'nom')
            ->orderBy('nom')
            ->get();

        // Récupérer les commandes pour les formulaires
        $commandes = Commande::with('client')
             ->select('id', 'client_id')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(function ($commande) {
                return [
                    'id' => $commande->id,
                    'numero' => $commande->id,
                    'client' => $commande->client->nom ?? 'Client inconnu'
                ];
            });

        return view('admin.dashboard.index', compact(
            'stats',
            'periode',
            'newCount',
            'activities',
            'livraisons',
            'terrains',
            'chauffeurs',
            'commandes'
        ));
    }

    public function stats(Request $request)
    {
        $periode = $request->get('periode', 'mois');
        $stats = $this->dashboardService->getDashboardStats($periode);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
