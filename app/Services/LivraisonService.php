<?php

namespace App\Services;

use App\Models\Livraison;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class LivraisonService
{
    public function getAllLivraisons(array $filters = [])
    {
        $query = Livraison::with(['terrain', 'chauffeur', 'commande']);

        // Filtres
        if (isset($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['date_debut'])) {
            $query->whereDate('date_livraison', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->whereDate('date_livraison', '<=', $filters['date_fin']);
        }

        return $query->latest()->paginate(15);
    }

    public function getLivraisonById(int $id): ?Livraison
    {
        return Livraison::with(['terrain', 'chauffeur', 'commande'])->find($id);
    }

    public function createLivraison(array $data): Livraison
    {
        return Livraison::create($data);
    }

    public function updateLivraison(Livraison $livraison, array $data): Livraison
    {
        $livraison->update($data);
        return $livraison;
    }

    public function deleteLivraison(Livraison $livraison): bool
    {
        return $livraison->delete();
    }

  // Récupérer les terrains actifs (role = 'terrain')
public function getTerrainsActifs(): Collection
{
    return User::where('role', 'terrain')
        ->where('active', true)
        ->select('id', 'nom', 'email')
        ->get();
}

// Récupérer les chauffeurs actifs (role = 'chauffeur')
public function getChauffeursActifs(): Collection
{
    return User::where('role', 'chauffeur')
        ->where('active', true)
        ->select('id', 'nom', 'email')
        ->get();
}

// Récupérer les commandes prêtes pour livraison
public function getCommandesALivrer(): Collection
{
    return Commande::whereIn('statut', ['en_attente', 'a_livrer', 'payee'])
        ->with(['client:id,nom'])
        ->select('id', 'numero', 'client_id', 'montant_total', 'statut')
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get();
}

    public function getCommandesEnAttente(): Collection
    {
        return Commande::where('statut', 'en_attente')
            ->with(['client', 'commercial'])
            ->latest()
            ->limit(50)
            ->get(['id', 'numero', 'client_id', 'montant_total', 'statut']);
    }

    public function getStatsLivraisons(): array
    {
        return [
            'total' => Livraison::count(),
            'en_attente' => Livraison::where('statut', 'en_attente')->count(),
            'en_cours' => Livraison::where('statut', 'en_cours')->count(),
            'livrees' => Livraison::where('statut', 'livree')->count(),
            'annulees' => Livraison::where('statut', 'annulee')->count(),
        ];
    }

    public function updateStatutLivraison(int $id, string $statut): bool
    {
        $livraison = Livraison::find($id);

        if (!$livraison) {
            return false;
        }

        $livraison->statut = $statut;
        return $livraison->save();
    }
}
