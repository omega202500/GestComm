@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    <span data-translate="add_client_title">Ajouter un client</span>
                </h1>
                <p class="text-muted mb-0" data-translate="add_client_subtitle">Remplissez les informations du client</p>
            </div>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <span data-translate="back">Retour</span>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
           <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">
                            <span data-translate="client_name">Nom du client</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nom') is-invalid @enderror"
                               id="nom"
                               name="nom"
                               value="{{ old('nom') }}"
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">
                            <span data-translate="client_phone">Téléphone</span>
                        </label>
                        <input type="tel"
                               class="form-control @error('telephone') is-invalid @enderror"
                               id="telephone"
                               name="telephone"
                               value="{{ old('telephone') }}">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <span data-translate="client_email">Email</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="zone_id" class="form-label">
                            <span data-translate="client_zone">Zone</span>
                        </label>
                        <select class="form-select @error('zone_id') is-invalid @enderror" id="zone_id" name="zone_id">
                            <option value="">Sélectionner une zone</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('zone_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="adresse" class="form-label">
                            <span data-translate="client_address">Adresse</span>
                        </label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror"
                                  id="adresse"
                                  name="adresse"
                                  rows="2">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="solde" class="form-label">
                            <span data-translate="client_balance">Solde initial</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               class="form-control @error('solde') is-invalid @enderror"
                               id="solde"
                               name="solde"
                               value="{{ old('solde', 0) }}">
                        <small class="text-muted">Montant en FCFA</small>
                        @error('solde')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        <span data-translate="cancel">Annuler</span>
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        <span data-translate="save">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
