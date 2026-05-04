<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Zone;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients (page HTML)
     */
    public function index(Request $request)
    {
        $clients = Client::with(['zone'])
            ->withCount('commandes')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Si c'est une requête AJAX/AJAX, retourner JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $clients
            ]);
        }
        
        // Sinon, retourner la vue HTML
        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $zones = Zone::all();
        return view('clients.create', compact('zones'));
    }

    /**
     * Enregistre un nouveau client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'zone_id' => 'nullable|integer',
            'adresse' => 'nullable|string',
            'solde' => 'nullable|numeric|min:0'
        ]);

        $client = Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client créé avec succès');
    }

    /**
     * Affiche un client spécifique
     */
    public function show($id)
    {
        $client = Client::with(['zone', 'commandes'])->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $zones = Zone::all();
        return view('clients.edit', compact('client', 'zones'));
    }

    /**
     * Met à jour un client
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'zone_id' => 'nullable|integer',
            'adresse' => 'nullable|string',
            'solde' => 'nullable|numeric|min:0'
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès');
    }

    /**
     * Supprime un client
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès');
    }

    /**
     * Retourne le nombre de clients (pour les badges)
     */
    public function count()
    {
        return response()->json(['count' => Client::count()]);
    }
}
