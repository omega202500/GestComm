@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-box text-primary me-2"></i>
                    <span data-translate="products_title">Gestion des Produits</span>
                </h1>
                <p class="text-muted mb-0" data-translate="products_subtitle">Liste et gestion de tous les produits</p>
            </div>
            <a href="{{ route('produits.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <span data-translate="add_product">Ajouter un produit</span>
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
            <h5 class="mb-0" data-translate="products_list">Liste des produits</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
    <thead>
         <tr>
            <th data-translate="product_photo">Photo</th>      <!-- COLONNE PHOTO -->
            <th data-translate="product_id">ID</th>
            <th data-translate="product_name">Nom</th>
            <th data-translate="category">Catégorie</th>
            <th data-translate="unit_price">Prix unitaire</th>
            <th data-translate="stock">Stock</th>
            <th data-translate="unit">Unité</th>
            <th data-translate="stock_status">Statut</th>
            <th data-translate="actions">Actions</th>
         </tr>
    </thead>
    <tbody>
        @forelse($produits as $produit)
         <tr>
            <!-- COLONNE PHOTO (ici) -->
            <td class="text-center">
                @if($produit->photo)
                    <img src="{{ Storage::url($produit->photo) }}"
                         width="40"
                         height="40"
                         style="object-fit: cover; border-radius: 6px;"
                         alt="{{ $produit->nom }}">
                @else
                    <i class="bi bi-image text-muted fs-4"></i>
                @endif
            </td>
            <td>{{ $produit->id }}</td>
            <td><strong>{{ $produit->nom }}</strong></td>
            <td><span class="badge bg-info">{{ $produit->categorie }}</span></td>
            <td class="text-primary fw-bold">{{ number_format($produit->prix_unitaire, 0, ',', ' ') }} FCFA</td>
            <td>{{ $produit->stock }}</td>
            <td>{{ $produit->unite ?? 'pièce' }}</td>
            <td>
                @if($produit->stock <= 0)
                    <span class="badge bg-danger">Rupture</span>
                @elseif($produit->stock < 10)
                    <span class="badge bg-warning">Stock faible</span>
                @else
                    <span class="badge bg-success">En stock</span>
                @endif
            </td>
            <td>
                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-sm btn-primary me-1">
                    <i class="bi bi-pencil"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct({{ $produit->id }}, '{{ addslashes($produit->nom) }}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
         </tr>
        @empty
         <tr>
            <td colspan="9" class="text-center py-5">
                <i class="bi bi-inbox text-muted fs-1"></i>
                <p class="text-muted mt-3">Aucun produit enregistré</p>
                <a href="{{ route('produits.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajouter un premier produit
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
function deleteProduct(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le produit "${name}" ?`)) {
        const form = document.getElementById('delete-form');
        form.action = `/produits/${id}`;
        form.submit();
    }
}
</script>
@endsection
