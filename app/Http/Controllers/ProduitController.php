<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produits::all();
        return view('produits.index', compact('produits'));
    }

    public function create()
    {
        return view('produits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(Produits::rules());

        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('produits', 'public');
            $validated['photo'] = $path;
        }

        Produits::create($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produits $produit)
    {
        return view('produits.show', compact('produit'));
    }

    public function edit(Produits $produit)
    {
        return view('produits.edit', compact('produit'));
    }

    public function update(Request $request, Produits $produit)
    {
        $validated = $request->validate(Produits::rules($produit->id));

        // Gérer l'upload de la nouvelle photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($produit->photo && Storage::disk('public')->exists($produit->photo)) {
                Storage::disk('public')->delete($produit->photo);
            }

            $path = $request->file('photo')->store('produits', 'public');
            $validated['photo'] = $path;
        }

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produits $produit)
    {
        // Supprimer la photo associée
        if ($produit->photo && Storage::disk('public')->exists($produit->photo)) {
            Storage::disk('public')->delete($produit->photo);
        }

        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    public function count()
    {
        $count = Produits::count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
