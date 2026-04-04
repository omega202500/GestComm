<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chargement;
use App\Models\Produit;
use App\Models\ChargementItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChargementController extends Controller
{
    // Liste des chargements (admin ou chauffeur)
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Chargement::with('items.produit');

        if ($user->role === 'chauffeur') {
            $query->where('user_id', $user->id);
        } elseif ($request->has('zone')) {
            $query->where('zone_id', $request->zone);
        }

        return response()->json($query->orderBy('date_chargement','desc')->paginate(20));
    }

    // Détail d'un chargement
    public function show($id)
    {
        $chargement = Chargement::with('items.produit')->findOrFail($id);
        return response()->json($chargement);
    }

    // Créer un chargement
    public function store(Request $request)
    {
        $user = $request->user();

        // autorisation : seuls chauffeurs peuvent enregistrer un chargement client-side
        if ($user->role !== 'chauffeur') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'date_chargement' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|integer|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        // Création du chargement
        $chargement = Chargement::create([
            'user_id' => $user->id,
            'date_chargement' => $request->date_chargement,
            'total_produits' => array_sum(array_column($request->items, 'quantite')),
            'statut' => 'en_cours'
        ]);

        // Créer les items et décrémenter (optionnel) le stock_central
        // foreach ($request->items as $it) {
        //     chargements::create([
        //         'chargement_id' => $chargement->id,
        //         'produit_id' => $it['produit_id'],
        //         'quantite' => $it['quantite'],
        //     ]);

        //     // Option : décrémenter stock central si tu veux
        //     $p = Produit::find($it['produit_id']);
        //     if ($p) {
        //         $p->stock_central = max(0, $p->stock_central - $it['quantite']);
        //         $p->save();
        //     }
        // }

        return response()->json(['message'=>'Chargement créé','chargement'=>$chargement], 201);
    }

    // Valider un chargement (admin ou chauffeur selon règle)
    public function validateChargement(Request $request, $id)
    {
        $user = $request->user();
        $chargement = Chargement::findOrFail($id);

        // Ici on autorise admin ou le chauffeur lui-même (tu peux restreindre)
        if ($user->role !== 'admin' && $user->id !== $chargement->user_id) {
            return response()->json(['message'=>'Non autorisé'], 403);
        }

        $chargement->statut = 'termine';
        $chargement->save();

        return response()->json(['message'=>'Chargement validé', 'chargement'=>$chargement]);
    }
}
