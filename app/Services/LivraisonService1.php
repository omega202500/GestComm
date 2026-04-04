<?php

namespace App\Services;

use App\Models\Livraison;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class LivraisonService
{
    // 🔴 NOUVELLE MÉTHODE : Récupérer toutes les livraisons pour l'API
    public function getAllLivraisons(array $filters = []): Collection
    {
        $query = Livraison::with([
            'commande:id,numero,statut,montant_total,client_id',
            'commande.client:id,nom',
            'terrain:id,nom,role',
            'chauffeur:id,nom,role'
        ])->orderBy('date_livraison', 'desc');

        // Appliquer les filtres
        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (!empty($filters['terrain_id'])) {
            $query->where('terrain_id', $filters['terrain_id']);
        }

        if (!empty($filters['chauffeur_id'])) {
            $query->where('chauffeur_id', $filters['chauffeur_id']);
        }

        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $query->whereBetween('date_livraison', [$filters['date_debut'], $filters['date_fin']]);
        }

        return $query->get(); // Retourne une collection simple pour l'API
    }

    // 🔴 NOUVELLE MÉTHODE : Créer une livraison (version API)
    public function createLivraison(array $data): Livraison
    {
        return DB::transaction(function () use ($data) {
            // Vérifier que la commande existe
            $commande = Commande::findOrFail($data['commande_id']);

            // Créer la livraison
            $livraison = Livraison::create([
                'terrain_id' => $data['terrain_id'],
                'chauffeur_id' => $data['chauffeur_id'],
                'commande_id' => $data['commande_id'],
                'date_livraison' => $data['date_livraison'],
                'statut' => $data['statut'] ?? 'en_attente',
                'notes' => $data['notes'] ?? null
            ]);

            // Optionnel : mettre à jour le statut de la commande
            if ($livraison->statut === 'en_cours') {
                $commande->update(['statut' => 'en_livraison']);
            }

            return $livraison->load(['commande.client', 'terrain', 'chauffeur']);
        });
    }

    // 🔴 NOUVELLE MÉTHODE : Mettre à jour une livraison (tous les champs)
    public function updateLivraison(Livraison $livraison, array $data): Livraison
    {
        return DB::transaction(function () use ($livraison, $data) {
            $livraison->update([
                'terrain_id' => $data['terrain_id'],
                'chauffeur_id' => $data['chauffeur_id'],
                'commande_id' => $data['commande_id'],
                'date_livraison' => $data['date_livraison'],
                'statut' => $data['statut'] ?? $livraison->statut,
                'notes' => $data['notes'] ?? $livraison->notes
            ]);

            // Mettre à jour le statut de la commande si nécessaire
            if (isset($data['statut'])) {
                $commande = $livraison->commande;
                if ($data['statut'] === 'livree') {
                    $commande->update(['statut' => 'livree']);
                } elseif ($data['statut'] === 'annulee') {
                    $commande->update(['statut' => 'annulee']);
                } elseif ($data['statut'] === 'en_cours') {
                    $commande->update(['statut' => 'en_livraison']);
                }
            }

            return $livraison->load(['commande.client', 'terrain', 'chauffeur']);
        });
    }

    // 🔴 NOUVELLE MÉTHODE : Supprimer une livraison
    public function deleteLivraison(Livraison $livraison): bool
    {
        return $livraison->delete();
    }

    // 🔴 NOUVELLE MÉTHODE : Récupérer les options pour les dropdowns
    public function getOptionsForLivraison(): array
    {
        return [
            'terrains' => $this->getTerrainsActifs(),
            'chauffeurs' => $this->getChauffeursActifs(),
            'commandes' => $this->getCommandesALivrer()
        ];
    }

    // 🔴 MÉTHODES UTILITAIRES POUR LES DROPDOWNS

    public function getTerrainsActifs(): Collection
    {
        return User::where('role', 'terrain')
            ->where('active', true)
            ->select('id', 'nom')
            ->get();
    }

    public function getChauffeursActifs(): Collection
    {
        return User::where('role', 'chauffeur')
            ->where('active', true)
            ->select('id', 'nom')
            ->get();
    }

    public function getCommandesALivrer(): Collection
    {
        return Commande::whereIn('statut', ['en_attente', 'a_livrer', 'payee'])
            ->with(['client:id,nom'])
            ->select('id', 'numero', 'client_id', 'montant_total', 'statut')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    // 🔴 MÉTHODE POUR LES STATISTIQUES (utilisée dans le dashboard)
    public function getStatsLivraisons(): array
    {
        $total = Livraison::count();

        return [
            'total' => $total,
            'en_attente' => Livraison::where('statut', 'en_attente')->count(),
            'en_cours' => Livraison::where('statut', 'en_cours')->count(),
            'livrees' => Livraison::where('statut', 'livree')->count(),
            'annulees' => Livraison::where('statut', 'annulee')->count(),
        ];
    }

    // 🔴 MÉTHODES EXISTANTES (conservées pour la logique métier)

    public function getLivraisonsFiltrees($filtres = [])
    {
        $query = Livraison::with(['commande', 'terrain', 'chauffeur', 'commande.client'])
            ->orderBy('date_livraison', 'desc');

        if (!empty($filtres['statut'])) {
            $query->where('statut', $filtres['statut']);
        }

        if (!empty($filtres['terrain_id'])) {
            $query->where('terrain_id', $filtres['terrain_id']);
        }

        if (!empty($filtres['chauffeur_id'])) {
            $query->where('chauffeur_id', $filtres['chauffeur_id']);
        }

        if (!empty($filtres['date_debut']) && !empty($filtres['date_fin'])) {
            $query->whereBetween('date_livraison', [$filtres['date_debut'], $filtres['date_fin']]);
        }

        return $query->paginate(20);
    }

    public function programmerLivraison(array $data)
    {
        return DB::transaction(function () use ($data) {
            $commande = Commande::findOrFail($data['commande_id']);

            if ($commande->statut !== 'a_livrer') {
                throw new \Exception('La commande n\'est pas prête pour livraison');
            }

            $livraison = Livraison::create($data);
            $commande->update(['statut' => 'en_livraison']);

            return $livraison;
        });
    }

    public function mettreAJourStatutLivraison($livraisonId, $statut, $notes = null)
    {
        return DB::transaction(function () use ($livraisonId, $statut, $notes) {
            $livraison = Livraison::findOrFail($livraisonId);

            $updateData = ['statut' => $statut];
            if ($notes) {
                $updateData['notes'] = $notes;
            }

            $livraison->update($updateData);

            if ($statut === 'livree') {
                $livraison->commande->update(['statut' => 'livree']);
            } elseif ($statut === 'annulee') {
                $livraison->commande->update(['statut' => 'annulee']);
            }

            return $livraison;
        });
    }

    public function assignerChauffeur($livraisonId, $chauffeurId)
    {
        $livraison = Livraison::findOrFail($livraisonId);
        $livraison->update([
            'chauffeur_id' => $chauffeurId,
            'statut' => 'en_cours'
        ]);

        return $livraison;
    }

    public function getStatistiquesLivraisons($dateDebut = null, $dateFin = null)
    {
        $query = Livraison::select(
            'statut',
            DB::raw('COUNT(*) as total'),
            DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, date_livraison)) as delai_moyen_heures')
        );

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_livraison', [$dateDebut, $dateFin]);
        }

        return $query->groupBy('statut')->get();
    }

    public function getPerformanceChauffeurs($dateDebut = null, $dateFin = null)
    {
        $query = Livraison::select(
            'chauffeur_id',
            DB::raw('COUNT(*) as total_livraisons'),
            DB::raw('SUM(CASE WHEN statut = "livree" THEN 1 ELSE 0 END) as livraisons_reussies'),
            DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, date_livraison)) as temps_moyen_minutes')
        )->whereNotNull('chauffeur_id')
         ->with('chauffeur');

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_livraison', [$dateDebut, $dateFin]);
        }

        return $query->groupBy('chauffeur_id')->orderBy('livraisons_reussies', 'desc')->get();
    }
}
