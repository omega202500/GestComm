<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ChargementController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RetourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\VersementController;
use App\Http\Controllers\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route de test PING (AJOUTEZ CECI)
Route::get('/ping', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

Route::post('/login', [AuthController::class, 'login']);

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
    // Récupérer l'utilisateur courant
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Routes CRUD
    Route::apiResource('users', UserController::class);
    Route::apiResource('zones', ZoneController::class);
    Route::apiResource('produits', ProduitController::class);
    Route::apiResource('commandes', CommandeController::class);
    Route::apiResource('ventes', VenteController::class);
    Route::apiResource('versements', VersementController::class);
    Route::apiResource('chargements', ChargementController::class);
    Route::apiResource('retours', RetourController::class);

    // Routes supplémentaires
    Route::get('commandes/{id}/items', [CommandeController::class, 'getItems']);
    Route::get('commercials/{id}/commandes', [UserController::class, 'getCommandes']);
    Route::get('dashboard/stats', [CommandeController::class, 'getDashboardStats']);

    // Chargements spécifiques
    Route::get('/chargements', [ChargementController::class, 'index']);
    Route::get('/chargements/{id}', [ChargementController::class, 'show']);
    Route::post('/chargements', [ChargementController::class, 'store']);
    Route::post('/chargements/{id}/validate', [ChargementController::class, 'validateChargement']);

    // Livraisons
    Route::get('/livraisons', [App\Http\Controllers\LivraisonController::class, 'index']);
    Route::get('/livraisons/{id}', [App\Http\Controllers\LivraisonController::class, 'show']);
    Route::post('/livraisons/{id}/finalize', [App\Http\Controllers\LivraisonController::class, 'finalize']);
    Route::post('/livraisons/{id}/confirm', [App\Http\Controllers\LivraisonController::class, 'confirm']);
});
