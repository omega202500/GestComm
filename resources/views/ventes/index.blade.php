@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <h1 class="h3 fw-bold mb-1">Gestion des Ventes</h1>
        <p class="text-muted mb-0">Liste de toutes les ventes</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des ventes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr><th>ID</th><th>Client</th><th>Commercial</th><th>Montant</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($ventes as $vente)
                        <tr>
                            <td>{{ $vente->id }}</td>
                            <td>{{ $vente->client->nom ?? 'N/A' }}</td>
                            <td>{{ $vente->commercial->nom ?? 'N/A' }}</td>
                            <td>{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $vente->created_at? $vente->created_at->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Aucune vente enregistrée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $ventes->links() }}
        </div>
    </div>
</div>
@endsection
