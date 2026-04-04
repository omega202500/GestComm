<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        // Récupérer toutes les zones
        $zones = DB::table('zones')->get();
        
        // Récupérer toutes les catégories
        $categories = DB::table('categories')->get();
        
        // Compter les produits
        $produitsCount = DB::table('produits')->count();
        
        return view('test', [
            'zones' => $zones,
            'categories' => $categories,
            'produitsCount' => $produitsCount,
            'message' => 'Connexion à la base de données réussie!'
        ]);
    }
    
    public function testDb()
    {
        try {
            DB::connection()->getPdo();
            return "Connexion à la base de données réussie! Nom de la base: " . DB::connection()->getDatabaseName();
        } catch (\Exception $e) {
            return "Erreur de connexion: " . $e->getMessage();
        }
    }
}