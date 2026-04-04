@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    <span data-translate="edit_product">Modifier le produit</span>
                </h1>
                <p class="text-muted mb-0" data-translate="edit_product_info">Modifier les informations du produit</p>
            </div>
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <span data-translate="back">Retour</span>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">
                            <span data-translate="product_name">Nom du produit</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nom') is-invalid @enderror"
                               id="nom"
                               name="nom"
                               value="{{ old('nom', $produit->nom) }}"
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="categorie" class="form-label">
                            <span data-translate="category">Catégorie</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('categorie') is-invalid @enderror"
                               id="categorie"
                               name="categorie"
                               value="{{ old('categorie', $produit->categorie) }}"
                               required>
                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="prix_unitaire" class="form-label">
                            <span data-translate="unit_price">Prix unitaire (FCFA)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               class="form-control @error('prix_unitaire') is-invalid @enderror"
                               id="prix_unitaire"
                               name="prix_unitaire"
                               value="{{ old('prix_unitaire', $produit->prix_unitaire) }}"
                               required>
                        @error('prix_unitaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">
                            <span data-translate="stock_quantity">Quantité en stock</span>
                        </label>
                        <input type="number"
                               class="form-control @error('stock') is-invalid @enderror"
                               id="stock"
                               name="stock"
                               value="{{ old('stock', $produit->stock) }}">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="unite" class="form-label">
                            <span data-translate="unit">Unité</span>
                        </label>
                        <select class="form-select" id="unite" name="unite">
                            <option value="pièce" {{ old('unite', $produit->unite) == 'pièce' ? 'selected' : '' }}>Pièce</option>
                            <option value="kg" {{ old('unite', $produit->unite) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                            <option value="litre" {{ old('unite', $produit->unite) == 'litre' ? 'selected' : '' }}>Litre</option>
                            <option value="mètre" {{ old('unite', $produit->unite) == 'mètre' ? 'selected' : '' }}>Mètre</option>
                            <option value="boîte" {{ old('unite', $produit->unite) == 'boîte' ? 'selected' : '' }}>Boîte</option>
                            <option value="sac" {{ old('unite', $produit->unite) == 'sac' ? 'selected' : '' }}>Sac</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="photo" class="form-label">
                            <span data-translate="product_photo">Photo du produit</span>
                        </label>

                        @if($produit->photo)
                            <div class="mb-2">
                                <label class="form-label">Photo actuelle :</label><br>
                                <img src="{{ Storage::url($produit->photo) }}"
                                     alt="{{ $produit->nom }}"
                                     style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                            </div>
                        @endif

                        <input type="file"
                               class="form-control @error('photo') is-invalid @enderror"
                               id="photo"
                               name="photo"
                               accept="image/*">
                        <small class="text-muted" data-translate="photo_help">Formats acceptés: JPG, PNG, GIF (max 2 Mo). Laissez vide pour conserver la photo actuelle.</small>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <div id="photo-preview" class="mt-2" style="display: none;">
                            <label class="form-label">Aperçu :</label>
                            <img id="preview-image" src="#" alt="Aperçu" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        <span data-translate="cancel">Annuler</span>
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        <span data-translate="update">Mettre à jour</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Aperçu de la photo avant upload
    document.getElementById('photo').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-image');
            preview.src = e.target.result;
            document.getElementById('photo-preview').style.display = 'block';
        }
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        } else {
            document.getElementById('photo-preview').style.display = 'none';
        }
    });
</script>
@endsection
