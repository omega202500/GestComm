<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'phone' => 'required|string',
            'role' => 'required|in:chauffeur,terrain'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('telephone', $request->phone)
                    ->where('role', $request->role)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun compte trouvé avec ce numéro'
            ], 404);
        }

        if (!$user->statut) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est désactivé'
            ], 403);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'telephone' => $user->telephone,
                'role' => $user->role
            ]
        ]);
    }
}
