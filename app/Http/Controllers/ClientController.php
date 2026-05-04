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
        
        // Détecter si c'est une requête AJAX ou API
        $isAjax = $request->ajax() || 
                  $request->wantsJson() || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                  $request->is('api/*');
        
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'data' => $clients
            ]);
        }
        
        // Retourner la vue HTML
        return view('clients.index', compact('clients'));
    }

    // Les autres méthodes restent identiques...
    public function create()
    {
        $zones = Zone::all();
        return view('clients.create', compact('zones'));
    }

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

    public function show($id)
    {
        $client = Client::with(['zone', 'commandes'])->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $zones = Zone::all();
        return view('clients.edit', compact('client', 'zones'));
    }

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

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès');
    }

    public function count()
    {
        return response()->json(['count' => Client::count()]);
    }
}
