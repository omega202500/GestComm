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
use App\Http\Controllers\ClientController;

// ============================
// ROUTES PUBLIQUES
// ============================

Route::post('/', function () {
    return redirect()->route('login');
});

 Route::get('/session-test', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'cookies' => request()->cookies->all()
    ]);
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
// ============================
// AUTHENTIFICATION
// ============================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================
// ROUTES PROTÉGÉES (ADMIN)
// ============================

// Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


    // -------- DASHBOARD --------
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/stats', [DashboardController::class, 'stats'])->name('admin.dashboard.stats');

    // Route pour obtenir le nombre de produits
    Route::get('/produits/count', function() {
        $count = \App\Models\Produits::count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    })->middleware('auth');



     // Routes pour les produits
    Route::resource('produits', ProduitController::class);
    Route::get('/produits/count', [ProduitController::class, 'count'])->name('produits.count');

    // -------- USERS --------
   Route::get('/users', function () {
    $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
    $commerciaux = \App\Models\User::whereIn('role', ['commercial', 'terrain', 'chauffeur'])->get();
    return view('users.index', compact('admins', 'commerciaux'));
})->name('users.index');

    Route::get('/check-auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user(),
            'session_id' => session()->getId()
        ]);
    });


    // Routes pour les utilisateurs
    Route::resource('users', UserController::class);

    // Routes pour les clients
    Route::resource('clients', ClientController::class);

    Route::get('/users/{id}/data', [UserController::class, 'getData'])->name('users.data');

    // -------- FORMULAIRES CREATE --------
    // Route::get('/commandes/create', fn() => "Formulaire commande - En construction")->name('commandes.create');
    // Route::get('/ventes/create', fn() => "Formulaire vente - En construction")->name('ventes.create');
    // Route::get('/clients/create', fn() => "Formulaire client - En construction")->name('clients.create');
    // Route::get('/produits/create', fn() => "Formulaire produit - En construction")->name('produits.create');
    // Route::get('/livraisons/create', fn() => "Formulaire livraison - En construction")->name('livraisons.create');
    // Route::get('/users/create', fn() => "Formulaire utilisateur - En construction")->name('users.create');

    // ============================
    // LIVRAISONS - Routes CRUD
    // ============================

    // Routes API pour les opérations CRUD (utilisées par AJAX)
    Route::prefix('admin')->group(function () {
        Route::post('/livraisons', [LivraisonController::class, 'store'])->name('admin.livraisons.store');
        Route::put('/livraisons/{id}', [LivraisonController::class, 'update'])->name('admin.livraisons.update');
        Route::delete('/livraisons/{id}', [LivraisonController::class, 'destroy'])->name('admin.livraisons.destroy');
    });

    // Route AJAX pour récupérer les livraisons
    Route::get('/admin/livraisons', [LivraisonController::class, 'index'])->name('livraisons.index');
    Route::get('/admin/livraisons-list', function () {
        $livraisons = \App\Models\Livraison::with(['commande.client', 'terrain', 'chauffeur'])->get()->map(function ($l) {
            return [
                'id' => $l->id,
                'commande_numero' => $l->commande->numero ?? 'N/A',
                'client_nom' => $l->commande->client->nom ?? 'N/A',
                'terrain_nom' => $l->terrain->nom ?? 'N/A',
                'chauffeur_nom' => $l->chauffeur->nom ?? 'N/A',
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
    })->middleware('auth');

    // Routes Web pour l'affichage
    Route::get('/livraisons', [LivraisonController::class, 'index'])->name('livraisons.index');
    Route::get('/livraisons-list', function() {
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

    // ============================
    // ACTIVITÉS ADMIN
    // ============================

    Route::prefix('admin')->group(function () {
        Route::post('/activities/{activity}/validate', [ActivityController::class, 'validateActivity'])->name('activities.validate');
        Route::post('/activities/{activity}/reject', [ActivityController::class, 'rejectActivity'])->name('activities.reject');
    });

    // ============================
    // PROFIL UTILISATEUR
    // ============================

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // ============================
    // CHANGEMENT DE LANGUE (AJAX)
    // ============================

   Route::post('/change-language', function (Request $request) {
    $locale = $request->input('lang');
    $supportedLocales = ['fr', 'en', 'es'];

    if (in_array($locale, $supportedLocales)) {
        session(['locale' => $locale]);
        App::setLocale($locale);

        return response()->json([
            'success' => true,
            'message' => 'Langue changée avec succès',
            'locale' => $locale
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Langue non supportée'
    ], 400);
})->name('change.language')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    // ROUTE DE COMPATIBILITÉ (pour l'ancien code JavaScript)
    Route::middleware(['auth'])->post('/password/change', [ProfileController::class, 'changePassword'])->name('password.change.ajax');

//});


