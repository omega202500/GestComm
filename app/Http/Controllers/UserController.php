<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
           $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
            $commerciaux = User::whereIn('role', ['commercial', 'terrain', 'chauffeur'])->get();
            return view('users.index', compact('admins', 'commerciaux'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,commercial,terrain,chauffeur',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $validated['photo'] = $path;
        }

        User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès'
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,commercial,terrain,chauffeur',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('avatars', 'public');
            $validated['photo'] = $path;
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Empêcher la suppression du super admin (id = 1)
        if ($user->id === 1 || $user->role === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer le super administrateur'
            ], 403);
        }

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    public function getData($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
   }
}
