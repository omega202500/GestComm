@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-person-badge text-primary me-2"></i>
                    <span data-translate="client_details">Détails du client</span>
                </h1>
                <p class="text-muted mb-0">{{ $client->nom }}</p>
            </div>
            <div>
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary me-1">
                    <i class="bi bi-pencil me-1"></i>
                    <span data-translate="edit">Modifier</span>
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    <span data-translate="back">Retour</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> <span data-translate="client_info">Informations client</span></h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>ID :</th>
                            <td>{{ $client->id }}</td>
                        </tr>
                        <tr>
                            <th>Nom :</th>
                            <td><strong>{{ $client->nom }}</strong></td>
                        </tr>
                        <tr>
                            <th>Téléphone :</th>
                            <td>{{ $client->telephone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Zone :</th>
                            <td>{{ $client->zone->nom ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Adresse :</th>
                            <td>{{ $client->adresse ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Solde :</th>
                            <td>
                                @if(($client->solde ?? 0) > 0)
                                    <span class="badge bg-warning">{{ number_format($client->solde ?? 0, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="badge bg-success">0 FCFA</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date création :</th>
                            <td>{{ $client->created_at ? $client->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> <span data-translate="order_history">Historique des commandes</span></h5>
                </div>
                <div class="card-body">
                    @if($historique->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th data-translate="order_id">N° Commande</th>
                                        <th data-translate="order_date">Date</th>
                                        <th data-translate="commercial">Commercial</th>
                                        <th data-translate="total">Montant</th>
                                        <th data-translate="status">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historique as $commande)
                                    <tr>
                                        <td>{{ $commande->id }}</td>
                                        <td>{{ $commande->date_commande ? $commande->date_commande->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $commande->commercial->nom ?? '-' }}</td>
                                        <td>{{ number_format($commande->montant_total ?? 0, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            @if($commande->statut == 'livree')
                                                <span class="badge bg-success">Livrée</span>
                                            @elseif($commande->statut == 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($commande->statut == 'annulee')
                                                <span class="badge bg-danger">Annulée</span>
                                            @else
                                                <span class="badge bg-info">{{ $commande->statut }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted fs-1"></i>
                            <p class="text-muted mt-2">Aucune commande pour ce client</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
