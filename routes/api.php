<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\VenteController;
use App\Http\Controllers\Api\VersementController;
use App\Http\Controllers\Api\ChargementController;
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
   // CLIENTS
    // Route::get('/clients', [ClientController::class, 'index']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
    Route::get('/clients/recherche', [ClientController::class, 'search']);

    // PRODUITS
    Route::get('/produits', [ProduitController::class, 'index']);
    Route::get('/produits/{id}', [ProduitController::class, 'show']);
    Route::get('/produits/stock/alerte', [ProduitController::class, 'stockAlerte']);
 // Commandes
    Route::post('/commandes', [CommandeController::class, 'store']);
    Route::get('/commandes/mine', [CommandeController::class, 'myCommandes']);
    Route::get('/commandes/{id}', [CommandeController::class, 'show']);
    Route::patch('/commandes/{id}/statut', [CommandeController::class, 'updateStatut']);
  // ======================
   // ROUTES PROTÉGÉES
   // ======================
   Route::middleware('auth:sanctum')->group(function () {

    // UTILISATEUR COURANT
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
     Route::get('/clients', [ClientController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);

 

    // VENTES
    Route::post('/ventes', [VenteController::class, 'store']);
    Route::get('/ventes/stats/jour', [VenteController::class, 'statsJour']);
    Route::get('/ventes/mine', [VenteController::class, 'myVentes']);
    Route::get('/ventes/{id}', [VenteController::class, 'show']);

    // VERSEMENTS
    Route::post('/versements', [VersementController::class, 'store']);
    Route::get('/versements/mine', [VersementController::class, 'myVersements']);

    // CHARGEMENTS
    Route::post('/chargements', [ChargementController::class, 'store']);
    Route::get('/chargements/mine', [ChargementController::class, 'myChargements']);

    // RETOURS
    Route::post('/retours', [RetourController::class, 'store']);
    Route::get('/retours/mine', [RetourController::class, 'myRetours']);
    Route::get('/retours/motifs', [RetourController::class, 'motifs']);

    // LIVRAISONS
    Route::get('/livraisons', [App\Http\Controllers\LivraisonController::class, 'index']);
    Route::get('/livraisons/{id}', [App\Http\Controllers\LivraisonController::class, 'show']);
    Route::post('/livraisons/{id}/finalize', [App\Http\Controllers\LivraisonController::class, 'finalize']);
    Route::post('/livraisons/{id}/confirm', [App\Http\Controllers\LivraisonController::class, 'confirm']);

    // RAPPORTS
    Route::get('/rapports/journalier', [App\Http\Controllers\RapportController::class, 'journalier']);
    Route::get('/rapports/performance', [App\Http\Controllers\RapportController::class, 'performance']);

    // GÉOLOCALISATION
    Route::post('/position', [UserController::class, 'updatePosition']);

    // ACTIVITÉS
    Route::post('/activities', [App\Http\Controllers\ActivityController::class, 'store']);

    // CRUD ADMIN
    Route::apiResource('users', UserController::class);
    Route::apiResource('zones', ZoneController::class);
    Route::apiResource('produits_admin', ProduitController::class);
    Route::apiResource('ventes_admin', VenteController::class);
    Route::apiResource('versements_admin', VersementController::class);
    Route::apiResource('chargements_admin', ChargementController::class);
    Route::apiResource('retours_admin', RetourController::class);


    Route::get('/chargements', [ChargementController::class, 'index']);
    Route::get('/chargements/{id}', [ChargementController::class, 'show']);
    Route::post('/chargements/{id}/validate', [ChargementController::class, 'validateChargement']);
});
