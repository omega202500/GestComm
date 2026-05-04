<?php

namespace App\Http\Controllers;

use App\Models\Versement;  // ← Majuscule (corrigé)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VersementController extends Controller
{
    // ============================
    // LISTE DES VERSEMENTS
    // ============================
    public function index(Request $request)
    {
        $versements = Versement::with(['commercial', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $versements->items(),
                'total'   => $versements->total(),
            ]);
        }

        return view('versements.index', compact('versements'));
    }

    // ============================
    // FORMULAIRE DE CRÉATION
    // ============================
    public function create()
    {
        return view('versements.create');
    }

    // ============================
    // ENREGISTRER UN VERSEMENT
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'montant'   => 'required|numeric|min:1',
            'reference' => 'nullable|string|max:100',
            'date'      => 'nullable|date',
            'notes'     => 'nullable|string',
        ]);

        try {
            $versement = Versement::create([
                'commercial_id' => Auth::id(),
                'montant'       => $request->montant,
                'reference'     => $request->reference,
                'date'          => $request->date ?? now()->toDateString(),
                'notes'         => $request->notes,
                'statut'        => 'en_attente',
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Versement enregistré avec succès',
                'versement'  => $versement,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ============================
    // DÉTAIL D'UN VERSEMENT
    // ============================
    public function show($id)
    {
        $versement = Versement::with(['commercial', 'client'])->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'data' => $versement]);
        }

        return view('versements.show', compact('versement'));
    }

    // ============================
    // FORMULAIRE D'ÉDITION
    // ============================
    public function edit($id)
    {
        $versement = Versement::findOrFail($id);
        return view('versements.edit', compact('versement'));
    }

    // ============================
    // METTRE À JOUR UN VERSEMENT
    // ============================
    public function update(Request $request, $id)
    {
        $versement = Versement::findOrFail($id);

        $request->validate([
            'montant'   => 'required|numeric|min:1',
            'reference' => 'nullable|string|max:100',
            'date'      => 'nullable|date',
            'notes'     => 'nullable|string',
            'statut'    => 'nullable|in:en_attente,valide,rejete',
        ]);

        $versement->update($request->only(['montant','reference','date','notes','statut']));

        return response()->json([
            'success'   => true,
            'message'   => 'Versement mis à jour',
            'versement' => $versement,
        ]);
    }

    // ============================
    // SUPPRIMER UN VERSEMENT
    // ============================
    public function destroy($id)
    {
        $versement = Versement::findOrFail($id);
        $versement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Versement supprimé',
        ]);
    }

    // ============================
    // COMPTER LES VERSEMENTS
    // DOIT être déclaré AVANT Route::resource() dans web.php
    // ============================
    public function count()
    {
        return response()->json([
            'success' => true,
            'count'   => Versement::count(),
        ]);
    }

    // ============================
    // STATS POUR LE DASHBOARD
    // DOIT être déclaré AVANT Route::resource() dans web.php
    // ============================
    public function stats()
    {
        return response()->json([
            'success'    => true,
            'total'      => Versement::sum('montant') ?? 0,
            'count'      => Versement::count(),
            'valides'    => Versement::where('statut', 'valide')->count(),
            'en_attente' => Versement::where('statut', 'en_attente')->count(),
            'rejetes'    => Versement::where('statut', 'rejete')->count(),
        ]);
    }

    // ============================
    // COMPTER LES VERSEMENTS EN ATTENTE
    // ============================
    public function countPending()
    {
        // Utilise 'statut' au lieu de 'valide' (booléen)
        // Adaptez selon votre colonne réelle en base de données
        $count = Versement::where('statut', 'en_attente')->count();

        return response()->json([
            'success' => true,
            'count'   => $count,
        ]);
    }

    // ============================
    // VALIDER UN VERSEMENT (admin)
    // ============================
    public function valider($id)
    {
        $versement = Versement::findOrFail($id);
        $versement->update(['statut' => 'valide']);

        return response()->json([
            'success' => true,
            'message' => 'Versement validé avec succès',
        ]);
    }

    // ============================
    // REJETER UN VERSEMENT (admin)
    // ============================
    public function rejeter(Request $request, $id)
    {
        $versement = Versement::findOrFail($id);
        $versement->update([
            'statut' => 'rejete',
            'notes'  => $request->raison ?? $versement->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Versement rejeté',
        ]);
    }
}
