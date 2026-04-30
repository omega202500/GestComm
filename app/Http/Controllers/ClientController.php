<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Zone;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $clients = $this->clientService->getClientsAvecCommandes();

        // Retourner toujours la vue HTML pour l'interface admin
        // Le JSON est géré par les routes API (api/clients)
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $zones = Zone::all();
        return view('clients.create', compact('zones'));
    }

    public function store(ClientRequest $request)
    {
        $client = $this->clientService->creerClient($request->validated());
        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès');
    }

    public function show($id)
    {
        $client = Client::with(['zone', 'commandes.commercial'])->findOrFail($id);
        $historique = $this->clientService->getHistoriqueCommandes($id);

        return view('clients.show', compact('client', 'historique'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $zones = Zone::all();
        return view('clients.edit', compact('client', 'zones'));
    }

    public function update(ClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());
        // ou
        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client mis à jour');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès');
    }

    public function statistiques()
    {
        $statistiques = $this->clientService->getStatistiquesClientsParZone();
        return view('clients.statistiques', compact('statistiques'));
    }
    public function count() {
    return response()->json([
        'success' => true,
        'count'   => Client::whereDate('created_at', today())->count()
    ]);
}

}
