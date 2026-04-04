<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Afficher la page du profil utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20'
        ]);
        
        $user->update($request->only(['nom', 'email', 'telephone']));
        
        return redirect()->back()->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Mettre à jour la photo de profil
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Supprimer l'ancienne photo si elle existe
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
        
        // Enregistrer la nouvelle photo
        $path = $request->file('photo')->store('avatars', 'public');
        $user->update(['photo' => $path]);
        
        return response()->json([
            'success' => true,
            'message' => 'Photo de profil mise à jour',
            'photo_url' => Storage::url($path)
        ]);
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'L\'ancien mot de passe est incorrect.'
            ], 422);
        }
        
        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        // Optionnel : Déconnecter l'utilisateur après changement
        Auth::logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Mot de passe changé avec succès. Veuillez vous reconnecter.'
        ]);
    }
}