<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Livraison;
use Illuminate\Support\Facades\DB;

class CommandeService
{
    public function getCommandesFiltrees($filtres = [])
    {
        $query = Commande::with(['client', 'commercial', 'zone', 'livraison'])
            ->orderBy('date_commande', 'desc');

        if (!empty($filtres['statut'])) {
            $query->where('statut', $filtres['statut']);
        }

        if (!empty($filtres['zone_id'])) {
            $query->where('zone_id', $filtres['zone_id']);
        }

        if (!empty($filtres['commercial_id'])) {
            $query->where('commercial_id', $filtres['commercial_id']);
        }

        if (!empty($filtres['date_debut']) && !empty($filtres['date_fin'])) {
            $query->whereBetween('date_commande', [$filtres['date_debut'], $filtres['date_fin']]);
        }

        return $query->paginate(20);
    }

    public function creerCommande(array $data)
    {
        return DB::transaction(function () use ($data) {
            $commande = Commande::create($data);
            
            // Créer une livraison associée si nécessaire
            if ($data['statut'] === 'a_livrer') {
                Livraison::create([
                    'commande_id' => $commande->id,
                    'terrain_id' => $data['commercial_id'],
                    'statut' => 'en_attente',
                ]);
            }
            
            return $commande;
        });
    }

    public function mettreAJourStatut($commandeId, $statut)
    {
        $commande = Commande::findOrFail($commandeId);
        $commande->update(['statut' => $statut]);
        
        // Mettre à jour la livraison si elle existe
        if ($commande->livraison) {
            $commande->livraison->update(['statut' => $statut]);
        }
        
        return $commande;
    }

    public function getStatistiquesCommandes($dateDebut = null, $dateFin = null)
    {
        $query = Commande::select(
            'statut',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(montant_total) as total_montant')
        );

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_commande', [$dateDebut, $dateFin]);
        }

        return $query->groupBy('statut')->get();
    }

    public function getChiffreAffairesParZone($dateDebut = null, $dateFin = null)
    {
        $query = Commande::select(
            'zone_id',
            DB::raw('SUM(montant_total) as chiffre_affaires'),
            DB::raw('COUNT(*) as total_commandes')
        )->where('statut', '!=', 'annulee');

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_commande', [$dateDebut, $dateFin]);
        }

        return $query->groupBy('zone_id')->with('zone')->get();
    }
}