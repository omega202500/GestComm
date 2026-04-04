@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="main-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold mb-1">
                            <i class="bi bi-truck text-primary me-2"></i>
                            {{ isset($livraison) ? 'Modifier la Livraison' : 'Nouvelle Livraison' }}
                        </h1>
                        <p class="text-muted mb-0">
                            {{ isset($livraison) ? 'Modifiez les informations de la livraison' : 'Programmez une nouvelle livraison' }}
                        </p>
                    </div>
                    <a href="{{ route('livraisons.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ isset($livraison) ? route('livraisons.update', $livraison->id) : route('livraisons.store') }}"
                          method="POST">
                        @csrf
                        @if(isset($livraison))
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <!-- Terrain -->
                            <div class="col-md-6">
                                <label for="terrain_id" class="form-label">Terrain <span class="text-danger">*</span></label>
                                <select class="form-select @error('terrain_id') is-invalid @enderror"
                                        id="terrain_id"
                                        name="terrain_id"
                                        required>
                                    <option value="">Sélectionner un terrain</option>
                                    @foreach($terrains as $terrain)
                                        <option value="{{ $terrain->id }}"
                                            {{ old('terrain_id', $livraison->terrain_id ?? '') == $terrain->id ? 'selected' : '' }}>
                                            {{ $terrain->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('terrain_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Chauffeur -->
                            <div class="col-md-6">
                                <label for="chauffeur_id" class="form-label">Chauffeur <span class="text-danger">*</span></label>
                                <select class="form-select @error('chauffeur_id') is-invalid @enderror"
                                        id="chauffeur_id"
                                        name="chauffeur_id"
                                        required>
                                    <option value="">Sélectionner un chauffeur</option>
                                    @foreach($chauffeurs as $chauffeur)
                                        <option value="{{ $chauffeur->id }}"
                                            {{ old('chauffeur_id', $livraison->chauffeur_id ?? '') == $chauffeur->id ? 'selected' : '' }}>
                                            {{ $chauffeur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chauffeur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commande -->
                            <div class="col-md-12">
                                <label for="commande_id" class="form-label">Commande <span class="text-danger">*</span></label>
                                <select class="form-select @error('commande_id') is-invalid @enderror"
                                        id="commande_id"
                                        name="commande_id"
                                        required>
                                    <option value="">Sélectionner une commande</option>
                                    @foreach($commandes as $commande)
                                        <option value="{{ $commande->id }}"
                                            {{ old('commande_id', $livraison->commande_id ?? '') == $commande->id ? 'selected' : '' }}>
                                            {{ $commande->numero }} - {{ $commande->client->nom ?? 'Client inconnu' }}
                                            ({{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA)
                                        </option>
                                    @endforeach
                                </select>
                                @error('commande_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date Livraison -->
                            <div class="col-md-6">
                                <label for="date_livraison" class="form-label">Date de Livraison <span class="text-danger">*</span></label>
                                <input type="datetime-local"
                                       class="form-control @error('date_livraison') is-invalid @enderror"
                                       id="date_livraison"
                                       name="date_livraison"
                                       value="{{ old('date_livraison', isset($livraison) ? $livraison->date_livraison->format('Y-m-d\TH:i') : '') }}"
                                       required>
                                @error('date_livraison')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="col-md-6">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select @error('statut') is-invalid @enderror"
                                        id="statut"
                                        name="statut">
                                    <option value="en_attente" {{ old('statut', $livraison->statut ?? 'en_attente') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="en_cours" {{ old('statut', $livraison->statut ?? '') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="livree" {{ old('statut', $livraison->statut ?? '') == 'livree' ? 'selected' : '' }}>Livrée</option>
                                    <option value="annulee" {{ old('statut', $livraison->statut ?? '') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="4"
                                          placeholder="Ajoutez des notes supplémentaires...">{{ old('notes', $livraison->notes ?? '') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('livraisons.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ isset($livraison) ? 'Mettre à jour' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
