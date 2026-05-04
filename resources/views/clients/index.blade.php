@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Gestion des Clients</h1>
                <p class="text-muted mb-0">Liste et gestion de tous les clients</p>
            </div>
            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un client
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des clients</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Zone</th>
                            <th>Commandes</th>
                            <th>Solde</th>
                            <th>Date création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td><strong>{{ $client->nom }}</strong></td>
                            <td>{{ $client->telephone ?? '-' }}</td>
                            <td>{{ $client->zone->nom ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $client->commandes_count ?? 0 }}</span></td>
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
                                <p class="text-muted mt-3">Aucun client enregistré</p>
                                <a href="{{ route('clients.create') }}" class="btn btn-primary mt-2">Ajouter un premier client</a>
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
