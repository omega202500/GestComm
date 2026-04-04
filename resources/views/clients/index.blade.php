@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-people text-primary me-2"></i>
                    <span data-translate="clients_title">Gestion des Clients</span>
                </h1>
                <p class="text-muted mb-0" data-translate="clients_subtitle">Liste et gestion de tous les clients</p>
            </div>
            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <span data-translate="add_client">Ajouter un client</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i> <span data-translate="clients_list">Liste des clients</span></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th data-translate="client_id">ID</th>
                            <th data-translate="client_name">Nom</th>
                            <th data-translate="client_phone">Téléphone</th>
                            <th data-translate="client_zone">Zone</th>
                            <th data-translate="client_orders">Commandes</th>
                            <th data-translate="client_balance">Solde</th>
                            <th data-translate="created_at">Date création</th>
                            <th data-translate="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td><strong>{{ $client->nom }}</strong></td>
                            <td>{{ $client->telephone ?? '-' }}</td>
                            <td>{{ $client->zone->nom ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $client->commandes_count ?? 0 }}</span>
                            </td>
                            <td>
                                @if(($client->solde ?? 0) > 0)
                                    <span class="badge bg-warning">{{ number_format($client->solde ?? 0, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="badge bg-success">0 FCFA</span>
                                @endif
                            </td>
                            <td>{{ $client->created_at ? $client->created_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-info me-1" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary me-1" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteClient({{ $client->id }}, '{{ addslashes($client->nom) }}')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                                <p class="text-muted mt-3" data-translate="no_clients">Aucun client enregistré</p>
                                <a href="{{ route('clients.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    <span data-translate="add_first_client">Ajouter un premier client</span>
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteClient(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le client "${name}" ?`)) {
        const form = document.getElementById('delete-form');
        form.action = `/clients/${id}`;
        form.submit();
    }
}
</script>
@endsection
