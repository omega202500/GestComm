<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Connexion utilisateur (sans mot de passe)
     * Authentification uniquement par numéro de téléphone et rôle
     */
    public function login(Request $request)
    {
        try {
            // Validation simplifiée (sans mot de passe)
            $validator = validator($request->all(), [
                'phone' => 'required|string',
                'role' => 'required|in:chauffeur,terrain'
            ], [
                'phone.required' => 'Le numéro de téléphone est obligatoire',
                'role.required' => 'Le type de commercial est obligatoire',
                'role.in' => 'Le rôle doit être chauffeur ou terrain'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Rechercher l'utilisateur par téléphone et rôle (sans mot de passe)
            $user = User::where('telephone', $validated['phone'])
                        ->where('role', $validated['role'])
                        ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun compte trouvé avec ce numéro et ce rôle'
                ], 401);
            }

            // Vérifier si le compte est actif
            if (!$user->statut) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre compte est désactivé. Veuillez contacter l\'administrateur.'
                ], 403);
            }

            // Supprimer les anciens tokens
            $user->tokens()->delete();

            // Créer un token d'authentification
            $token = $user->createToken('mobile-token', [$validated['role']])->plainTextToken;

            // Retourner la réponse
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'telephone' => $user->telephone,
                    'email' => $user->email,
                    'role' => $user->role,
                    'statut' => $user->statut,
                    'photo' => $user->photo ? asset('storage/' . $user->photo) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'telephone' => $user->telephone,
                'email' => $user->email,
                'role' => $user->role,
                'statut' => $user->statut,
                'photo' => $user->photo ? asset('storage/' . $user->photo) : null
            ]
        ]);
    }
}
