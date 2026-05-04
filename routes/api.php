<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\VenteController;
use App\Http\Controllers\Api\VersementController;
use App\Http\Controllers\Api\ChargementController;
use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\RetourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ======================
// ROUTES PUBLIQUES (sans authentification)
// ======================

// Route de test PING
Route::get('/ping', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// Route de connexion
Route::post('/login', [AuthController::class, 'login']);

// Route de test DB
Route::get('/test-db-connection', function() {
    try {
        DB::connection()->getPdo();
        return response()->json(['success' => true, 'message' => 'Connexion DB réussie']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

// ======================
// ROUTES PROTÉGÉES (nécessitent authentification)
// ======================
Route::middleware('auth:sanctum')->group(function () {

    // UTILISATEUR COURANT
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // ======================
    // CLIENTS
    // ======================
    Route::get('/clients', [ClientController::class, 'index']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
    Route::get('/clients/recherche', [ClientController::class, 'search']);
    Route::get('/clients/count', [ClientController::class, 'count']);
    
    Route::get('/commerciaux/liste', function() {
    $commerciaux = \App\Models\User::whereIn('role', ['commercial', 'terrain', 'chauffeur'])->get();
    return response()->json([
        'success' => true,
        'data' => $commerciaux->map(function($user) {
            return [
                'id' => $user->id,
                'nom' => $user->nom,
                'role' => $user->role,
                'telephone' => $user->telephone,
                'email' => $user->email,
                'statut' => $user->statut
            ];
        })
    ]);
})->middleware('auth:sanctum');

    // ======================
    // PRODUITS
    // ======================
    Route::get('/produits', [ProduitController::class, 'index']);
    Route::get('/produits/{id}', [ProduitController::class, 'show']);
    Route::get('/produits/stock/alerte', [ProduitController::class, 'stockAlerte']);

    // ======================
    // COMMANDES (CORRIGÉ)
    // ======================
    Route::post('/commandes', [CommandeController::class, 'store']);     // POST pour création
    Route::get('/commandes', [CommandeController::class, 'index']);       // GET pour liste
    Route::get('/commandes/mine', [CommandeController::class, 'myCommandes']);
    Route::get('/commandes/{id}', [CommandeController::class, 'show']);
    Route::patch('/commandes/{id}/statut', [CommandeController::class, 'updateStatut']);
    Route::get('/commandes/count', [CommandeController::class, 'count']);

    // ======================
    // VENTES
    // ======================
    Route::post('/ventes', [VenteController::class, 'store']);
    Route::get('/ventes/stats/jour', [VenteController::class, 'statsJour']);
    Route::get('/ventes/mine', [VenteController::class, 'myVentes']);
    Route::get('/ventes/{id}', [VenteController::class, 'show']);

    // ======================
    // VERSEMENTS
    // ======================
    Route::post('/versements', [VersementController::class, 'store']);
    Route::get('/versements/mine', [VersementController::class, 'myVersements']);

    // ======================
    // CHARGEMENTS
    // ======================
    Route::post('/chargements', [ChargementController::class, 'store']);
    Route::get('/chargements/mine', [ChargementController::class, 'myChargements']);
    Route::get('/chargements', [ChargementController::class, 'index']);
    Route::get('/chargements/{id}', [ChargementController::class, 'show']);
    Route::post('/chargements/{id}/validate', [ChargementController::class, 'validateChargement']);

    // ======================
    // RETOURS
    // ======================
    Route::post('/retours', [RetourController::class, 'store']);
    Route::get('/retours/mine', [RetourController::class, 'myRetours']);
    Route::get('/retours/motifs', [RetourController::class, 'motifs']);

    // ======================
    // LIVRAISONS
    // ======================
    Route::get('/livraisons', [App\Http\Controllers\LivraisonController::class, 'index']);
    Route::get('/livraisons/{id}', [App\Http\Controllers\LivraisonController::class, 'show']);
    Route::post('/livraisons/{id}/finalize', [App\Http\Controllers\LivraisonController::class, 'finalize']);
    Route::post('/livraisons/{id}/confirm', [App\Http\Controllers\LivraisonController::class, 'confirm']);

    // ======================
    // RAPPORTS
    // ======================
    Route::get('/rapports/journalier', [App\Http\Controllers\RapportController::class, 'journalier']);
    Route::get('/rapports/performance', [App\Http\Controllers\RapportController::class, 'performance']);

    // ======================
    // GÉOLOCALISATION
    // ======================
    Route::post('/position', [UserController::class, 'updatePosition']);

    // ======================
    // ACTIVITÉS
    // ======================
    Route::post('/activities', [App\Http\Controllers\ActivityController::class, 'store']);

    // ======================
    // CRUD ADMIN
    // ======================
    Route::apiResource('users', UserController::class);
    Route::apiResource('zones', ZoneController::class);
    
    // ======================
    // STATISTIQUES DASHBOARD
    // ======================
    
    // Statistiques globales du dashboard
    Route::get('/dashboard/stats', function() {
        return response()->json([
            'success' => true,
            'data' => [
                'chiffre_affaires' => \App\Models\Commande::where('statut', 'livree')->sum('montant_total'),
                'total_commandes' => \App\Models\Commande::count(),
                'commandes_livrees' => \App\Models\Commande::where('statut', 'livree')->count(),
                'commandes_en_attente' => \App\Models\Commande::where('statut', 'en_attente')->count(),
                'total_ventes' => \App\Models\Vente::sum('montant_total'),
                'total_quantite' => \App\Models\Vente::sum('total_quantite'),
                'nb_ventes' => \App\Models\Vente::count(),
                'total_versements' => \App\Models\Versement::sum('montant'),
                'versements_valides' => \App\Models\Versement::where('valide', true)->sum('montant'),
                'versements_en_attente' => \App\Models\Versement::where('valide', false)->sum('montant'),
                'nb_clients' => \App\Models\Client::count(),
                'nouveaux_clients' => \App\Models\Client::where('created_at', '>=', now()->subDays(30))->count()
            ]
        ]);
    });
    
    // Statistiques des ventes
    Route::get('/ventes/stats', function() {
        return response()->json([
            'success' => true,
            'total_ventes' => \App\Models\Vente::sum('montant_total'),
            'total_quantite' => \App\Models\Vente::sum('total_quantite'),
            'nb_ventes' => \App\Models\Vente::count(),
            'ventes_jour' => \App\Models\Vente::whereDate('created_at', now()->toDateString())->sum('montant_total'),
            'ventes_mois' => \App\Models\Vente::whereMonth('created_at', now()->month)->sum('montant_total')
        ]);
    });
    
    // Statistiques des versements
    Route::get('/versements/stats', function() {
        return response()->json([
            'success' => true,
            'total' => \App\Models\Versement::sum('montant'),
            'valides' => \App\Models\Versement::where('valide', true)->sum('montant'),
            'en_attente' => \App\Models\Versement::where('valide', false)->sum('montant'),
            'nb_versements' => \App\Models\Versement::count(),
            'versements_jour' => \App\Models\Versement::whereDate('created_at', now()->toDateString())->sum('montant')
        ]);
    });
    
    // Performance des commerciaux
    Route::get('/performance/commerciaux', function() {
        $commerciaux = \App\Models\User::whereIn('role', ['commercial', 'terrain', 'chauffeur'])
            ->where('statut', true)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $commerciaux->map(function($user) {
                $ventes = \App\Models\Vente::where('commercial_id', $user->id);
                $commandes = \App\Models\Commande::where('commercial_id', $user->id);
                
                return [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'role' => $user->role,
                    'total_ventes' => $ventes->sum('montant_total'),
                    'total_commandes' => $commandes->sum('montant_total'),
                    'total_quantite_vendue' => $ventes->sum('total_quantite'),
                    'nb_commandes' => $commandes->count(),
                    'nb_ventes' => $ventes->count(),
                    'objectif' => 100000
                ];
            })->sortByDesc('total_ventes')->values()
        ]);
    });
    
    // Dernières commandes (pour le dashboard)
    Route::get('/dernieres-commandes', function() {
        $commandes = \App\Models\Commande::with(['client', 'commercial'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $commandes
        ]);
    });
    
    // Derniers clients (pour le dashboard)
    Route::get('/derniers-clients', function() {
        $clients = \App\Models\Client::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    });
});
