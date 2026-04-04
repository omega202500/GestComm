@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Carte de profil - Colonne gauche -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Mon Profil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <!-- Photo de profil -->
                    <div class="position-relative d-inline-block mb-3">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 class="rounded-circle" 
                                 width="150" 
                                 height="150"
                                 style="object-fit: cover;"
                                 alt="Photo de profil">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 150px; height: 150px; font-size: 48px;">
                                {{ strtoupper(substr($user->nom, 0, 1)) }}
                            </div>
                        @endif
                        
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="document.getElementById('photoInput').click()">
                            <i class="bi bi-camera"></i> Changer la photo
                        </button>
                        
                        <!-- CORRECTION ICI : Utiliser le bon nom de route -->
                        <form id="photoForm" action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                            @csrf
                            <input type="file" id="photoInput" name="photo" accept="image/*" onchange="uploadPhoto()">
                        </form>
                    </div>
                    
                    <h4 class="mt-3">{{ $user->nom }}</h4>
                    <p class="text-muted">
                        <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-person-badge me-1"></i> 
                        Rôle: {{ ucfirst($user->role) }}
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-calendar me-1"></i> 
                        Membre depuis: {{ $user->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Informations personnelles - Colonne droite -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- CORRECTION ICI : Utiliser le bon nom de route -->
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   name="nom" value="{{ old('nom', $user->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" 
                                   name="telephone" value="{{ old('telephone', $user->telephone ?? '') }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function uploadPhoto() {
    const form = document.getElementById('photoForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors du téléchargement de la photo');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}
</script>
@endsection