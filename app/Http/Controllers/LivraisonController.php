<?php

namespace App\Http\Controllers;

use App\Models\Livraison;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{
    /**
     * Afficher la liste des livraisons
     */
    public function index()
    {
        $livraisons = Livraison::with(['commande.client', 'chauffeur', 'terrain'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $terrains = User::where('role', 'terrain')
            ->where('statut', true)
            ->select('id', 'nom')
            ->get();

        $chauffeurs = User::where('role', 'chauffeur')
            ->where('statut', true)
            ->select('id', 'nom')
            ->get();

        $commandes = Commande::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('livraisons.index', compact('livraisons', 'terrains', 'chauffeurs', 'commandes'));
    }

    /**
     * Afficher une livraison spécifique
     */
    public function show($id)
    {
        $livraison = Livraison::with(['commande.client', 'chauffeur', 'terrain'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $livraison
        ]);
    }

    /**
     * Créer une nouvelle livraison (API)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'terrain_id' => 'required|exists:users,id',  // terrain_id fait référence à un User
            'chauffeur_id' => 'required|exists:users,id', // chauffeur_id fait référence à un User
            'date_livraison' => 'required|date',
            'statut' => 'required|in:en_attente,en_cours,livree,annulee',
            'notes' => 'nullable|string'
        ]);

        // Vérifier que les users ont bien les bons rôles
        $terrain = User::findOrFail($validated['terrain_id']);
        $chauffeur = User::findOrFail($validated['chauffeur_id']);

        if ($terrain->role !== 'terrain') {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur sélectionné n\'est pas un terrain'
            ], 422);
        }

        if ($chauffeur->role !== 'chauffeur') {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur sélectionné n\'est pas un chauffeur'
            ], 422);
        }

        $livraison = Livraison::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Livraison créée avec succès',
            'data' => $livraison
        ]);
    }

    /**
     * Mettre à jour une livraison (API)
     */
    public function update(Request $request, $id)
    {
        $livraison = Livraison::findOrFail($id);

        $validated = $request->validate([
            'commande_id' => 'sometimes|exists:commandes,id',
            'terrain_id' => 'sometimes|exists:users,id',
            'chauffeur_id' => 'sometimes|exists:users,id',
            'date_livraison' => 'sometimes|date',
            'statut' => 'sometimes|in:en_attente,en_cours,livree,annulee',
            'notes' => 'nullable|string'
        ]);

        // Vérifier les rôles si les IDs sont modifiés
        if (isset($validated['terrain_id'])) {
            $terrain = User::findOrFail($validated['terrain_id']);
            if ($terrain->role !== 'terrain') {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'utilisateur sélectionné n\'est pas un terrain'
                ], 422);
            }
        }

        if (isset($validated['chauffeur_id'])) {
            $chauffeur = User::findOrFail($validated['chauffeur_id']);
            if ($chauffeur->role !== 'chauffeur') {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'utilisateur sélectionné n\'est pas un chauffeur'
                ], 422);
            }
        }

        $livraison->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Livraison mise à jour avec succès',
            'data' => $livraison
        ]);
    }

    /**
     * Supprimer une livraison (API)
     */
    public function destroy($id)
    {
        $livraison = Livraison::findOrFail($id);
        $livraison->delete();

        return response()->json([
            'success' => true,
            'message' => 'Livraison supprimée avec succès'
        ]);
    }

    /**
     * Mettre à jour le statut d'une livraison
     */
    public function updateStatut(Request $request, $id)
    {
        $livraison = Livraison::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,livree,annulee'
        ]);

        $livraison->update([
            'statut' => $request->statut
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'data' => $livraison
        ]);
    }
}
