
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <h1 class="h3 fw-bold mb-1">Gestion des Commandes</h1>
        <p class="text-muted mb-0">Liste de toutes les commandes</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des commandes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr><th>ID</th><th>Client</th><th>Commercial</th><th>Montant</th><th>Statut</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($commandes as $commande)
                        <tr>
                            <td>{{ $commande->id }}</td>
                            <td>{{ $commande->client->nom ?? 'N/A' }}</td>
                            <td>{{ $commande->commercial->nom ?? 'N/A' }}</td>
                            <td>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($commande->statut == 'livree')
                                    <span class="badge bg-success">Livrée</span>
                                @elseif($commande->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($commande->statut == 'en_cours')
                                    <span class="badge bg-info">En cours</span>
                                @elseif($commande->statut == 'annulee')
                                    <span class="badge bg-danger">Annulée</span>
                                @else
                                    <span class="badge bg-secondary">{{ $commande->statut }}</span>
                                @endif
                            </td>
                            <td>{{ $commande->created_at? $commande->created_at->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Aucune commande enregistrée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $commandes->links() }}
        </div>
    </div>
</div>
@endsection
