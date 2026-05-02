<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VersementController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;

// ============================
// ROUTES PUBLIQUES
// ============================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/session-test', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'cookies' => request()->cookies->all()
    ]);
});

// ============================
// AUTHENTIFICATION
// ============================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================
// DASHBOARD ADMIN
// ============================
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/stats', [DashboardController::class, 'stats'])->name('admin.dashboard.stats');

// ============================
// IMPORTANT : Les routes "count" et routes spéciales
// DOIVENT être déclarées AVANT Route::resource()
// sinon Laravel les interprète comme un {id}
// ============================

// -------- COMMANDES : routes spéciales AVANT resource --------
Route::get('/commandes/count', [CommandeController::class, 'count'])->name('commandes.count');
Route::get('/commandes/stats', [CommandeController::class, 'getDashboardStats'])->name('commandes.stats');
// Ensuite le resource
Route::resource('commandes', CommandeController::class);

// -------- VENTES : routes spéciales AVANT resource --------
Route::get('/ventes/count', [VenteController::class, 'count'])->name('ventes.count');
Route::get('/ventes/stats', [VenteController::class, 'stats'])->name('ventes.stats');
// Ensuite le resource (ou routes manuelles si vous n'avez pas de resource)
Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
Route::get('/ventes/create', [VenteController::class, 'create'])->name('ventes.create');
Route::post('/ventes', [VenteController::class, 'store'])->name('ventes.store');
Route::get('/ventes/{id}', [VenteController::class, 'show'])->name('ventes.show');
Route::get('/ventes/{id}/edit', [VenteController::class, 'edit'])->name('ventes.edit');
Route::put('/ventes/{id}', [VenteController::class, 'update'])->name('ventes.update');
Route::delete('/ventes/{id}', [VenteController::class, 'destroy'])->name('ventes.destroy');

// -------- CLIENTS : routes spéciales AVANT resource --------
Route::get('/clients/count', [ClientController::class, 'count'])->name('clients.count');
Route::get('/clients/stats', [ClientController::class, 'statistiques'])->name('clients.stats');
// Ensuite le resource
Route::resource('clients', ClientController::class);

// -------- PRODUITS : routes spéciales AVANT resource --------
Route::get('/produits/count', [ProduitController::class, 'count'])->name('produits.count');
// Ensuite le resource
Route::resource('produits', ProduitController::class);

// -------- VERSEMENTS : routes spéciales AVANT les routes manuelles --------
Route::get('/versements/count', [VersementController::class, 'count'])->name('versements.count');
Route::get('/versements/stats', [VersementController::class, 'stats'])->name('versements.stats');
Route::get('/versements', [VersementController::class, 'index'])->name('versements.index');
Route::get('/versements/create', [VersementController::class, 'create'])->name('versements.create');
Route::post('/versements', [VersementController::class, 'store'])->name('versements.store');
Route::get('/versements/{id}', [VersementController::class, 'show'])->name('versements.show');
Route::get('/versements/{id}/edit', [VersementController::class, 'edit'])->name('versements.edit');
Route::put('/versements/{id}', [VersementController::class, 'update'])->name('versements.update');
Route::delete('/versements/{id}', [VersementController::class, 'destroy'])->name('versements.destroy');

// -------- UTILISATEURS --------
Route::get('/users', function () {
    $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
    $commerciaux = \App\Models\User::whereIn('role', ['commercial', 'terrain', 'chauffeur'])->get();
    return view('users.index', compact('admins', 'commerciaux'));
})->name('users.index');
Route::resource('users', UserController::class)->except(['index']);
Route::get('/users/{id}/data', [UserController::class, 'getData'])->name('users.data');

// -------- RAPPORTS --------
Route::get('/rapports', fn() => view('rapports.index'))->name('rapports.index');

// -------- LIVRAISONS --------
Route::prefix('admin')->group(function () {
    Route::get('/livraisons', [LivraisonController::class, 'index'])->name('livraisons.index');
    Route::post('/livraisons', [LivraisonController::class, 'store'])->name('admin.livraisons.store');
    Route::get('/livraisons/{id}', [LivraisonController::class, 'show'])->name('livraisons.show');
    Route::put('/livraisons/{id}', [LivraisonController::class, 'update'])->name('admin.livraisons.update');
    Route::delete('/livraisons/{id}', [LivraisonController::class, 'destroy'])->name('admin.livraisons.destroy');
    Route::patch('/livraisons/{id}/statut', [LivraisonController::class, 'updateStatut'])->name('livraisons.update-statut');

    Route::get('/livraisons-list', function () {
        $livraisons = \App\Models\Livraison::with(['commande.client', 'terrain', 'chauffeur'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($l) {
                return [
                    'id'              => $l->id,
                    'commande_numero' => $l->commande->numero ?? 'N/A',
                    'client_nom'      => $l->commande->client->nom ?? 'Client inconnu',
                    'terrain_nom'     => $l->terrain->nom ?? 'Terrain inconnu',
                    'chauffeur_nom'   => $l->chauffeur->nom ?? 'Chauffeur inconnu',
                    'date_livraison'  => $l->date_livraison,
                    'statut'          => $l->statut,
                    'commande_id'     => $l->commande_id,
                    'terrain_id'      => $l->terrain_id,
                    'chauffeur_id'    => $l->chauffeur_id,
                    'notes'           => $l->notes,
                ];
            });

        return response()->json(['success' => true, 'livraisons' => $livraisons]);
    })->name('livraisons.list');
});

// -------- ACTIVITÉS ADMIN --------
Route::prefix('admin')->group(function () {
    Route::post('/activities/{activity}/validate', [ActivityController::class, 'validateActivity'])->name('activities.validate');
    Route::post('/activities/{activity}/reject', [ActivityController::class, 'rejectActivity'])->name('activities.reject');
});

// -------- PROFIL --------
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
Route::post('/password/change', [ProfileController::class, 'changePassword'])->name('password.change.ajax');

// -------- CHANGEMENT DE LANGUE --------
Route::post('/change-language', function (Request $request) {
    $locale = $request->input('lang');
    $supportedLocales = ['fr', 'en', 'es'];

    if (in_array($locale, $supportedLocales)) {
        session(['locale' => $locale]);
        App::setLocale($locale);
        return response()->json(['success' => true, 'message' => 'Langue changée', 'locale' => $locale]);
    }

    return response()->json(['success' => false, 'message' => 'Langue non supportée'], 400);
})->name('change.language');

// -------- CHECK AUTH --------
Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user'          => auth()->user(),
        'session_id'    => session()->getId(),
    ]);
});
