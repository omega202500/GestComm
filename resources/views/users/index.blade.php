@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-people text-primary me-2"></i>
                    <span data-translate="users_management">Gestion des Utilisateurs</span>
                </h1>
                <p class="text-muted mb-0" data-translate="users_subtitle">Gérez les administrateurs et les commerciaux</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Onglets -->
    <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button" role="tab">
                <i class="bi bi-shield-lock me-1"></i>
                <span data-translate="admins">Administrateurs</span>
                <span class="badge bg-primary ms-1">{{ $admins->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="commerciaux-tab" data-bs-toggle="tab" data-bs-target="#commerciaux" type="button" role="tab">
                <i class="bi bi-person-badge me-1"></i>
                <span data-translate="commerciaux">Commerciaux</span>
                <span class="badge bg-success ms-1">{{ $commerciaux->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Onglet Administrateurs -->
        <div class="tab-pane fade show active" id="admins" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i> <span data-translate="admins_list">Liste des administrateurs</span></h5>
                    <button class="btn btn-primary btn-sm" onclick="openUserModal('admin')">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span data-translate="add_admin">Ajouter un administrateur</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th data-translate="photo">Photo</th>
                                    <th data-translate="name">Nom</th>
                                    <th data-translate="email">Email</th>
                                    <th data-translate="role">Rôle</th>
                                    <th data-translate="phone">Téléphone</th>
                                    <th data-translate="status">Statut</th>
                                    <th data-translate="created_at">Date création</th>
                                    <th data-translate="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                <tr>
                                    <td class="text-center">
                                        @if($admin->photo)
                                            <img src="{{ Storage::url($admin->photo) }}" width="40" height="40" style="object-fit: cover; border-radius: 50%;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($admin->nom, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $admin->nom }}</strong></td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        @if($admin->role === 'super_admin')
                                            <span class="badge bg-danger">Super Admin</span>
                                        @else
                                            <span class="badge bg-info">Admin</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->telephone ?? '-' }}</td>
                                    <td>
                                        @if($admin->statut)
                                            <span class="badge bg-success" data-translate="active">Actif</span>
                                        @else
                                            <span class="badge bg-secondary" data-translate="inactive">Inactif</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->created_at ? $admin->created_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="editUser({{ $admin->id }})" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($admin->id !== 1 && $admin->role !== 'super_admin')
                                            <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $admin->id }}, '{{ addslashes($admin->nom) }}')" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-inbox text-muted fs-1"></i>
                                        <p class="text-muted mt-3">Aucun administrateur</p>
                                        <button class="btn btn-primary mt-2" onclick="openUserModal('admin')">Ajouter un administrateur</button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet Commerciaux -->
        <div class="tab-pane fade" id="commerciaux" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i> <span data-translate="commerciaux_list">Liste des commerciaux</span></h5>
                    <button class="btn btn-primary btn-sm" onclick="openUserModal('commercial')">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span data-translate="add_commercial">Ajouter un commercial</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th data-translate="photo">Photo</th>
                                    <th data-translate="name">Nom</th>
                                    <th data-translate="email">Email</th>
                                    <th data-translate="role">Rôle</th>
                                    <th data-translate="phone">Téléphone</th>
                                    <th data-translate="status">Statut</th>
                                    <th data-translate="created_at">Date création</th>
                                    <th data-translate="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commerciaux as $commercial)
                                <tr>
                                    <td class="text-center">
                                        @if($commercial->photo)
                                            <img src="{{ Storage::url($commercial->photo) }}" width="40" height="40" style="object-fit: cover; border-radius: 50%;">
                                        @else
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($commercial->nom, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $commercial->nom }}</strong></td>
                                    <td>{{ $commercial->email }}</td>
                                    <td>
                                        @if($commercial->role === 'terrain')
                                            <span class="badge bg-info">Terrain</span>
                                        @elseif($commercial->role === 'chauffeur')
                                            <span class="badge bg-warning">Chauffeur</span>
                                        @else
                                            <span class="badge bg-success">Commercial</span>
                                        @endif
                                    </td>
                                    <td>{{ $commercial->telephone ?? '-' }}</td>
                                    <td>
                                        @if($commercial->statut)
                                            <span class="badge bg-success" data-translate="active">Actif</span>
                                        @else
                                            <span class="badge bg-secondary" data-translate="inactive">Inactif</span>
                                        @endif
                                    </td>
                                    <td>{{ $commercial->created_at ? $commercial->created_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="editUser({{ $commercial->id }})" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $commercial->id }}, '{{ addslashes($commercial->nom) }}')" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-inbox text-muted fs-1"></i>
                                        <p class="text-muted mt-3">Aucun commercial</p>
                                        <button class="btn btn-primary mt-2" onclick="openUserModal('commercial')">Ajouter un commercial</button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier Utilisateur -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="user_id" name="id">
                    <input type="hidden" id="user_role" name="role">
                    
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3" id="password_field">
                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted">Laissez vide pour conserver le mot de passe actuel (en modification)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone">
                    </div>
                    
                    <div class="mb-3">
                        <label for="role_select" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_select" name="role" required>
                            <option value="admin">Administrateur</option>
                            <option value="commercial">Commercial</option>
                            <option value="terrain">Terrain</option>
                            <option value="chauffeur">Chauffeur</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo de profil</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        <div id="photo_preview" class="mt-2" style="display: none;">
                            <img id="preview_img" src="#" style="max-width: 100px; border-radius: 50%;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRole = 'admin';

function openUserModal(role) {
    currentRole = role;
    document.getElementById('user_id').value = '';
    document.getElementById('userForm').reset();
    document.getElementById('user_role').value = role;
    document.getElementById('role_select').value = role === 'admin' ? 'admin' : 'commercial';
    document.getElementById('userModalTitle').innerHTML = role === 'admin' ? 'Ajouter un administrateur' : 'Ajouter un commercial';
    document.getElementById('password_field').style.display = 'block';
    document.getElementById('photo_preview').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

function editUser(id) {
    console.log('Editing user:', id);
    
    // Utiliser une route API dédiée au lieu de la route d'édition
    fetch(`/users/${id}/data`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('User data:', data);
        if (data.success) {
            const user = data.user;
            
            document.getElementById('user_id').value = user.id;
            document.getElementById('nom').value = user.nom;
            document.getElementById('email').value = user.email;
            document.getElementById('telephone').value = user.telephone || '';
            document.getElementById('statut').value = user.statut ? '1' : '0';
            document.getElementById('user_role').value = user.role;
            document.getElementById('role_select').value = user.role;
            document.getElementById('password_field').style.display = 'none';
            
            if (user.role === 'admin' || user.role === 'super_admin') {
                document.getElementById('userModalTitle').innerHTML = 'Modifier un administrateur';
            } else {
                document.getElementById('userModalTitle').innerHTML = 'Modifier un commercial';
            }
            
            if (user.photo) {
                document.getElementById('preview_img').src = '/storage/' + user.photo;
                document.getElementById('photo_preview').style.display = 'block';
            } else {
                document.getElementById('photo_preview').style.display = 'none';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        } else {
            showNotification('Erreur', data.message || 'Erreur lors du chargement', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur', 'Erreur lors du chargement de l\'utilisateur', 'danger');
    });
}
function saveUser() {
    const id = document.getElementById('user_id').value;
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    
    const url = id ? `/users/${id}` : '/users';
    
    if (id) {
        formData.append('_method', 'PUT');
    }
    
    // Désactiver le bouton pour éviter double soumission
    const saveBtn = document.querySelector('#userModal .btn-primary');
    const originalText = saveBtn ? saveBtn.innerHTML : 'Enregistrer';
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> En cours...';
    }
    
    console.log('Envoi de la requête à:', url);
    console.log('Données:', Object.fromEntries(formData));
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        console.log('Statut réponse:', response.status);
        const data = await response.json();
        console.log('Réponse:', data);
        
        if (!response.ok) {
            throw { status: response.status, data: data };
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
            if (modal) modal.hide();
            showNotification('Succès', data.message || 'Utilisateur enregistré avec succès', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Erreur', data.message || 'Erreur lors de l\'enregistrement', 'danger');
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        }
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        let errorMessage = 'Erreur lors de l\'enregistrement';
        
        if (error.data && error.data.message) {
            errorMessage = error.data.message;
        } else if (error.data && error.data.errors) {
            const errors = Object.values(error.data.errors).flat();
            errorMessage = errors.join(', ');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        showNotification('Erreur', errorMessage, 'danger');
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    });
}

function deleteUser(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${name} ?`)) {
        fetch(`/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Aperçu photo
document.getElementById('photo').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview_img').src = e.target.result;
        document.getElementById('photo_preview').style.display = 'block';
    }
    if (this.files[0]) {
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endsection