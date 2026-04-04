<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Vente;
use App\Models\User;
use App\Models\Produits;
use App\Models\Versement;
use App\Models\Livraison;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Activity;


class DashboardService
{
    public function getDashboardStats($periode = 'mois')
    {
        $dateRange = $this->getDateRange($periode);

        return [
            'chiffre_affaires' => $this->getChiffreAffaires($dateRange),
            'commandes' => $this->getCommandesStats($dateRange),
            'total_ventes' => $this->getTotalVentes($dateRange),
            'total_quantite' => $this->getTotalQuantiteVendue($dateRange),
            'versements' => $this->getVersementsStats($dateRange),
            'performance_commerciaux' => $this->getPerformanceCommerciaux($dateRange),
            'produits_plus_vendus' => $this->getProduitsPlusVendus($dateRange),
            'livraisons_stats' => $this->getLivraisonsStats($dateRange),
            'commandes_details' => $this->getCommandesDetails($dateRange),
        ];
    }
    public function countNewActivities(): int
{
    return Activity::where('status', 'pending')->count();
}

public function getRecentActivities(int $limit = 10)
{
    return Activity::latest()->take($limit)->get();
}

    private function getDateRange($periode)
    {
        $now = Carbon::now();

        return match($periode) {
            'jour' => [
                'start' => $now->startOfDay(),
                'end' => $now->endOfDay()
            ],
            'semaine' => [
                'start' => $now->startOfWeek(),
                'end' => $now->endOfWeek()
            ],
            'mois' => [
                'start' => $now->startOfMonth(),
                'end' => $now->endOfMonth()
            ],
            'annee' => [
                'start' => $now->startOfYear(),
                'end' => $now->endOfYear()
            ],
            default => [
                'start' => $now->startOfMonth(),
                'end' => $now->endOfMonth()
            ]
        };
    }

    private function getChiffreAffaires($dateRange)
    {
        // Chiffre d'affaires des commandes livrées
        return Commande::whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
            ->where('statut', 'livree')
            ->sum('montant_total');
    }

    private function getCommandesStats($dateRange)
    {
        return [
            'total' => Commande::whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])->count(),
            'en_attente' => Commande::whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
                ->where('statut', 'en_attente')->count(),
            'livrees' => Commande::whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
                ->where('statut', 'livree')->count(),
            'annulees' => Commande::whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
                ->where('statut', 'annulee')->count(),
        ];
    }

    private function getTotalVentes($dateRange)
    {
        return Vente::whereBetween('date_vente', [$dateRange['start'], $dateRange['end']])->count();
    }

    private function getTotalQuantiteVendue($dateRange)
    {
        // Quantité vendue à travers les ventes
        return Vente::whereBetween('date_vente', [$dateRange['start'], $dateRange['end']])
            ->sum('total_quantite');
    }

    private function getVersementsStats($dateRange)
    {
        return [
            'total' => Versement::whereBetween('date_versement', [$dateRange['start'], $dateRange['end']])
                ->sum('montant'),
            'valides' => Versement::whereBetween('date_versement', [$dateRange['start'], $dateRange['end']])
                ->where('valide', true)
                ->sum('montant'),
            'en_attente' => Versement::whereBetween('date_versement', [$dateRange['start'], $dateRange['end']])
                ->where('valide', false)
                ->sum('montant'),
        ];
    }

   private function getPerformanceCommerciaux($dateRange)
{
    // Pour les commandes (commercial_id)
    $commandesParCommercial = Commande::select('commercial_id', DB::raw('SUM(montant_total) as total_commandes'))
        ->whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
        ->where('statut', 'livree')
        ->groupBy('commercial_id')
        ->pluck('total_commandes', 'commercial_id');

    // Pour les ventes (commercial_id)
    $ventesParCommercial = Vente::select('commercial_id',
            DB::raw('SUM(montant_total) as total_ventes'),
            DB::raw('SUM(total_quantite) as total_quantite')
        )
        ->whereBetween('date_vente', [$dateRange['start'], $dateRange['end']])
        ->groupBy('commercial_id')
        ->get()
        ->keyBy('commercial_id');

    // Récupérer uniquement les terrains et chauffeurs (exclure admin)
    return User::whereIn('role', ['terrain', 'chauffeur'])  // ← Exclure 'admin' et 'user'
        ->where('statut', true)
        ->select('id', 'nom', 'email', 'role')
        ->get()
        ->map(function($user) use ($commandesParCommercial, $ventesParCommercial) {
            $user->total_commandes = $commandesParCommercial[$user->id] ?? 0;
            $user->total_ventes = $ventesParCommercial[$user->id]->total_ventes ?? 0;
            $user->total_quantite_vendue = $ventesParCommercial[$user->id]->total_quantite ?? 0;
            return $user;
        })
        ->sortByDesc('total_ventes')
        ->values();
}

    private function getProduitsPlusVendus($dateRange)
    {
        // Produits les plus vendus à travers les ventes
        return Vente::select('produits.id', 'produits.nom', 'produits.categorie', 'produits.prix_unitaire')
            ->selectRaw('SUM(ventes.total_quantite) as quantite_vendue')
            ->selectRaw('SUM(ventes.montant_total) as chiffre_affaires')
            ->join('produits', 'ventes.produit_id', '=', 'produits.id')
            ->whereBetween('ventes.date_vente', [$dateRange['start'], $dateRange['end']])
            ->groupBy('produits.id', 'produits.nom', 'produits.categorie', 'produits.prix_unitaire')
            ->orderBy('quantite_vendue', 'desc')
            ->limit(10)
            ->get();
    }

    private function getLivraisonsStats($dateRange)
    {
        return [
            'total' => Livraison::whereBetween('date_livraison', [$dateRange['start'], $dateRange['end']])->count(),
            'livrees' => Livraison::whereBetween('date_livraison', [$dateRange['start'], $dateRange['end']])
                ->where('statut', 'livree')->count(),
            'en_cours' => Livraison::whereBetween('date_livraison', [$dateRange['start'], $dateRange['end']])
                ->where('statut', 'en_cours')->count(),
        ];
    }

    private function getCommandesDetails($dateRange)
    {
        // Commandes avec relations
        return Commande::with(['commercial' => function($query) {
                $query->select('id', 'nom');
            }])
            ->with(['zone' => function($query) {
                $query->select('id', 'nom');
            }])
            ->with(['versements' => function($query) {
                $query->select('id', 'commande_id', 'montant', 'valide', 'date_versement');
            }])
            ->whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
            ->orderBy('date_commande', 'desc')
            ->limit(20)
            ->get();
    }


    public function getCommandesAvecDetails($dateRange = null)
    {
        if (!$dateRange) {
            $dateRange = $this->getDateRange('mois');
        }

        return Commande::select(
                'commandes.*',
                'users.nom as commercial_nom',
                'zones.nom as zone_nom',
                DB::raw('(SELECT COUNT(*) FROM ventes WHERE ventes.commande_id = commandes.id) as nombre_ventes'),
                DB::raw('(SELECT SUM(montant) FROM versements WHERE versements.commande_id = commandes.id AND valide = 1) as total_versements')
            )
            ->leftJoin('users', 'commandes.commercial_id', '=', 'users.id')
            ->leftJoin('zones', 'commandes.zone_id', '=', 'zones.id')
            ->whereBetween('commandes.date_commande', [$dateRange['start'], $dateRange['end']])
            ->orderBy('commandes.date_commande', 'desc')
            ->get();
    }

    public function getVentesAvecDetails($dateRange = null)
    {
        if (!$dateRange) {
            $dateRange = $this->getDateRange('mois');
        }

        return Vente::select(
                'ventes.*',
                'produits.nom as produit_nom',
                'produits.categorie as produit_categorie',
                'users.nom as commercial_nom',
                'commandes.numero as commande_numero',
                'commandes.statut as commande_statut'
            )
            ->join('produits', 'ventes.produit_id', '=', 'produits.id')
            ->leftJoin('users', 'ventes.commercial_id', '=', 'users.id')
            ->leftJoin('commandes', 'ventes.commande_id', '=', 'commandes.id')
            ->whereBetween('ventes.date_vente', [$dateRange['start'], $dateRange['end']])
            ->orderBy('ventes.date_vente', 'desc')
            ->get();
    }

    public function getStockProduitsAlerte($seuil = 10)
    {
        return Produits::where('stock', '<=', $seuil)
            ->orderBy('stock', 'asc')
            ->get(['id', 'nom', 'categorie', 'stock', 'prix_unitaire']);
    }

    public function getCommandesEnAttente()
    {
        return Commande::with(['commercial' => function($query) {
                $query->select('id', 'nom');
            }])
            ->with(['zone' => function($query) {
                $query->select('id', 'nom');
            }])
            ->where('statut', 'en_attente')
            ->orderBy('date_commande', 'asc')
            ->get();
    }

    public function getStatistiquesParCategorie($dateRange = null)
    {
        if (!$dateRange) {
            $dateRange = $this->getDateRange('mois');
        }

        return Vente::select('produits.categorie')
            ->selectRaw('COUNT(*) as nombre_ventes')
            ->selectRaw('SUM(ventes.total_quantite) as quantite_vendue')
            ->selectRaw('SUM(ventes.montant_total) as chiffre_affaires')
            ->join('produits', 'ventes.produit_id', '=', 'produits.id')
            ->whereBetween('ventes.date_vente', [$dateRange['start'], $dateRange['end']])
            ->groupBy('produits.categorie')
            ->orderBy('chiffre_affaires', 'desc')
            ->get();
    }

    public function getEvolutionChiffreAffaires($periode = 'mois')
    {
        $dateRange = $this->getDateRange($periode);
        $jours = $this->getJoursDansPeriode($dateRange);

        $chiffreAffairesParJour = Commande::select(
                DB::raw('DATE(date_commande) as date'),
                DB::raw('SUM(montant_total) as total')
            )
            ->whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
            ->where('statut', 'livree')
            ->groupBy(DB::raw('DATE(date_commande)'))
            ->orderBy('date', 'asc')
            ->pluck('total', 'date')
            ->toArray();

        // Remplir les jours sans données avec 0
        $evolution = [];
        foreach ($jours as $jour) {
            $dateStr = $jour->format('Y-m-d');
            $evolution[] = [
                'date' => $dateStr,
                'total' => $chiffreAffairesParJour[$dateStr] ?? 0
            ];
        }

        return $evolution;
    }

    public function getStatistiquesCommercial($commercialId, $dateRange = null)
    {
        if (!$dateRange) {
            $dateRange = $this->getDateRange('mois');
        }

        // Commandes du commercial
        $commandesStats = Commande::select(
                DB::raw('COUNT(*) as total_commandes'),
                DB::raw('SUM(montant_total) as ca_commandes')
            )
            ->where('commercial_id', $commercialId)
            ->whereBetween('date_commande', [$dateRange['start'], $dateRange['end']])
            ->first();

        // Ventes du commercial
        $ventesStats = Vente::select(
                DB::raw('COUNT(*) as total_ventes'),
                DB::raw('SUM(total_quantite) as total_quantite'),
                DB::raw('SUM(montant_total) as ca_ventes')
            )
            ->where('commercial_id', $commercialId)
            ->whereBetween('date_vente', [$dateRange['start'], $dateRange['end']])
            ->first();

        return [
            'commandes' => [
                'total' => $commandesStats->total_commandes ?? 0,
                'chiffre_affaires' => $commandesStats->ca_commandes ?? 0
            ],
            'ventes' => [
                'total' => $ventesStats->total_ventes ?? 0,
                'quantite' => $ventesStats->total_quantite ?? 0,
                'chiffre_affaires' => $ventesStats->ca_ventes ?? 0
            ]
        ];
    }

    private function getJoursDansPeriode($dateRange)
    {
        $jours = [];
        $current = Carbon::parse($dateRange['start']);
        $end = Carbon::parse($dateRange['end']);

        while ($current <= $end) {
            $jours[] = $current->copy();
            $current->addDay();
        }

        return $jours;
    }
}
