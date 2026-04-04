<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;  
use App\Http\Controllers\DashboardController;

// Routes publiques
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // 1. AJOUTEZ CETTE ROUTE CLIENTS (elle manque !)
    Route::get('/clients', function () {
        return "Page Clients - En construction";
    })->name('clients.index');
    
    // Routes temporaires pour éviter les erreurs
    Route::get('/commandes', function () {
        return "Page Commandes - En construction";
    })->name('commandes.index');
    
    Route::get('/produits', function () {
        return "Page Produits - En construction";
    })->name('produits.index');
    
    Route::get('/ventes', function () {
        return "Page Ventes - En construction";
    })->name('ventes.index');
    
    Route::get('/livraisons', function () {
        return "Page Livraisons - En construction";
    })->name('livraisons.index');
    
    Route::get('/versements', function () {
        return "Page Versements - En construction";
    })->name('versements.index');
    
    Route::get('/rapports', function () {
        return "Page Rapports - En construction";
    })->name('rapports');
    
    // 2. CORRIGEZ CETTE LIGNE : 'utilisateurs' → 'users'
    Route::get('/users', function () {
        $users = \App\Models\User::all();
        return view('users.index', compact('users'));
    })->name('users.index');  // Changez le nom si besoin
});