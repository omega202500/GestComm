<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;

class ClientService
{
    public function getClientsAvecCommandes()
    {
        return Client::withCount(['commandes as total_commandes'])
            ->withSum(['commandes as total_achats' => function($query) {
                $query->where('statut', '!=', 'annulee');
            }], 'montant_total')
            ->with('zone')
            ->orderBy('nom')
            ->get();
    }

    public function creerClient(array $data)
    {
        return Client::create($data);
    }

    public function mettreAJourClient($id, array $data)
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client;
    }

    public function getHistoriqueCommandes($clientId)
    {
        return Commande::where('client_id', $clientId)
            ->with(['commercial', 'zone'])
            ->orderBy('date_commande', 'desc')
            ->get();
    }

    public function getStatistiquesClientsParZone()
    {
        return Client::select('zone_id', DB::raw('COUNT(*) as total_clients'))
            ->with('zone')
            ->groupBy('zone_id')
            ->get();
    }
}