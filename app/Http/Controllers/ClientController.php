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
    // Si l'URL contient ?format=html, forcer la vue HTML
    if ($request->get('format') === 'html') {
        $clients = Client::with(['zone'])->withCount('commandes')->orderBy('created_at', 'desc')->get();
        return view('clients.index', compact('clients'));
    }
    
    // Si l'URL contient ?format=json, forcer le JSON
    if ($request->get('format') === 'json') {
        $clients = Client::with(['zone'])->withCount('commandes')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $clients]);
    }
    
    // Par défaut, pour une requête normale de navigateur, retourner HTML
    if (!$request->ajax() && !$request->wantsJson()) {
        $clients = Client::with(['zone'])->withCount('commandes')->orderBy('created_at', 'desc')->get();
        return view('clients.index', compact('clients'));
    }
    
    // Pour les requêtes AJAX
    $clients = Client::with(['zone'])->withCount('commandes')->orderBy('created_at', 'desc')->get();
    return response()->json(['success' => true, 'data' => $clients]);
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
