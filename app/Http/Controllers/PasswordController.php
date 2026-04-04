<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends controller
{
    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        // Validation des données
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'old_password.required' => 'L\'ancien mot de passe est requis.',
            'new_password.required' => 'Le nouveau mot de passe est requis.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            'new_password_confirmation.required' => 'La confirmation du mot de passe est requise.',
        ]);

        $user = Auth::user();

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors([
                'old_password' => 'L\'ancien mot de passe est incorrect.'
            ])->withInput();
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Déconnecter l'utilisateur pour qu'il se reconnecte avec le nouveau mot de passe
        Auth::logout();

        // Message de succès
        return redirect()->route('login')->with('success', 'Votre mot de passe a été changé avec succès. Veuillez vous reconnecter avec votre nouveau mot de passe.');
    }
}
