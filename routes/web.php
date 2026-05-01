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
// ROUTES ADMIN (sans middleware auth pour le moment)
// ============================

// -------- DASHBOARD --------
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/stats', [DashboardController::class, 'stats'])->name('admin.dashboard.stats');

// -------- COMMANDES --------
Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create');
Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
Route::get('/commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
Route::get('/commandes/{id}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
Route::put('/commandes/{id}', [CommandeController::class, 'update'])->name('commandes.update');
Route::delete('/commandes/{id}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
Route::get('/commandes/count', [CommandeController::class, 'count']);

// -------- VENTES --------
Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
Route::get('/ventes/create', [VenteController::class, 'create'])->name('ventes.create');
Route::post('/ventes', [VenteController::class, 'store'])->name('ventes.store');
Route::get('/ventes/{id}', [VenteController::class, 'show'])->name('ventes.show');
Route::get('/ventes/{id}/edit', [VenteController::class, 'edit'])->name('ventes.edit');
Route::put('/ventes/{id}', [VenteController::class, 'update'])->name('ventes.update');
Route::delete('/ventes/{id}', [VenteController::class, 'destroy'])->name('ventes.destroy');
Route::get('/ventes/count', [VenteController::class, 'count']);

// -------- CLIENTS --------
Route::resource('clients', ClientController::class);
Route::get('/clients/count', [ClientController::class, 'count']);

// -------- PRODUITS --------
Route::resource('produits', ProduitController::class);
Route::get('/produits/count', [ProduitController::class, 'count'])->name('produits.count');

// -------- VERSEMENTS --------
Route::get('/versements', [VersementController::class, 'index'])->name('versements.index');
Route::get('/versements/create', [VersementController::class, 'create'])->name('versements.create');
Route::post('/versements', [VersementController::class, 'store'])->name('versements.store');
Route::get('/versements/{id}', [VersementController::class, 'show'])->name('versements.show');
Route::get('/versements/{id}/edit', [VersementController::class, 'edit'])->name('versements.edit');
Route::put('/versements/{id}', [VersementController::class, 'update'])->name('versements.update');
Route::delete('/versements/{id}', [VersementController::class, 'destroy'])->name('versements.destroy');
Route::get('/versements/count-pending', [VersementController::class, 'countPending']);

// -------- UTILISATEURS --------
Route::resource('users', UserController::class);
Route::get('/users', function () {
    $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
    $commerciaux = \App\Models\User::whereIn('role', ['commercial', 'terrain', 'chauffeur'])->get();
    return view('users.index', compact('admins', 'commerciaux'));
})->name('users.index');
Route::get('/users/{id}/data', [UserController::class, 'getData'])->name('users.data');

// -------- RAPPORTS --------
Route::get('/rapports', fn() => view('rapports.index'))->name('rapports.index');

// -------- LIVRAISONS --------
Route::prefix('admin')->group(function () {
    Route::post('/livraisons', [LivraisonController::class, 'store'])->name('admin.livraisons.store');
    Route::put('/livraisons/{id}', [LivraisonController::class, 'update'])->name('admin.livraisons.update');
    Route::delete('/livraisons/{id}', [LivraisonController::class, 'destroy'])->name('admin.livraisons.destroy');
    Route::get('/livraisons', [LivraisonController::class, 'index'])->name('livraisons.index');
    Route::get('/livraisons-list', function () {
        $livraisons = \App\Models\Livraison::with(['commande.client', 'terrain', 'chauffeur'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($l) {
                return [
                    'id' => $l->id,
                    'commande_numero' => $l->commande->numero ?? 'N/A',
                    'client_nom' => $l->commande->client->nom ?? 'Client inconnu',
                    'terrain_nom' => $l->terrain->nom ?? 'Terrain inconnu',
                    'chauffeur_nom' => $l->chauffeur->nom ?? 'Chauffeur inconnu',
                    'date_livraison' => $l->date_livraison,
                    'statut' => $l->statut,
                    'commande_id' => $l->commande_id,
                    'terrain_id' => $l->terrain_id,
                    'chauffeur_id' => $l->chauffeur_id,
                    'notes' => $l->notes
                ];
            });

        return response()->json([
            'success' => true,
            'livraisons' => $livraisons
        ]);
    })->name('livraisons.list');
    Route::get('/livraisons/{id}', [LivraisonController::class, 'show'])->name('livraisons.show');
    Route::patch('/livraisons/{id}/statut', [LivraisonController::class, 'updateStatut'])->name('livraisons.update-statut');
});

// -------- ACTIVITÉS ADMIN --------
Route::prefix('admin')->group(function () {
    Route::post('/activities/{activity}/validate', [ActivityController::class, 'validateActivity'])->name('activities.validate');
    Route::post('/activities/{activity}/reject', [ActivityController::class, 'rejectActivity'])->name('activities.reject');
});

// -------- PROFIL UTILISATEUR --------
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

// -------- CHANGEMENT DE LANGUE (AJAX) --------
Route::post('/change-language', function (Request $request) {
    $locale = $request->input('lang');
    $supportedLocales = ['fr', 'en', 'es'];

    if (in_array($locale, $supportedLocales)) {
        session(['locale' => $locale]);
        App::setLocale($locale);
        return response()->json(['success' => true, 'message' => 'Langue changée avec succès', 'locale' => $locale]);
    }

    return response()->json(['success' => false, 'message' => 'Langue non supportée'], 400);
})->name('change.language');

// -------- COMPATIBILITÉ --------
Route::post('/password/change', [ProfileController::class, 'changePassword'])->name('password.change.ajax');

// -------- ROUTE CHECK AUTH --------
Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId()
    ]);
});
