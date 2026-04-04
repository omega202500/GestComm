<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'GestComm'))</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: #3490dc;
        }

        .main-content {
            padding: 40px;
            min-height: calc(100vh - 60px);
        }
    </style>

    @yield('scripts')
    @stack('styles')
</head>
<body>
    <!-- Header avec menu utilisateur -->
    <nav class="navbar">
        <div class="navbar-brand">
            {{ config('app.name', 'GestComm') }}
        </div>

        <div class="user-menu">
            @auth
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-decoration-none d-flex align-items-center"
                       data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-lock me-2"></i> Changer Mot de Passe
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logout-form">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    <i class="fas fa-sign-in-alt me-1"></i> Connexion
                </a>
            @endauth
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Modal Changer Mot de Passe -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Changer le mot de passe</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="passwordAlert" class="alert d-none" role="alert"></div>

                    <div class="mb-3">
                        <label class="form-label">Ancien mot de passe</label>
                        <input type="password" id="old_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" id="new_password" class="form-control" minlength="8" required>
                        <small class="text-muted">Minimum 8 caractères</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="new_password_confirmation" class="form-control" minlength="8" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success" onclick="changerMdp()">Confirmer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function changerMdp() {
        // Récupérer les valeurs
        const oldPwd = document.getElementById('old_password').value;
        const newPwd = document.getElementById('new_password').value;
        const confirmPwd = document.getElementById('new_password_confirmation').value;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Validation simple
        if (!oldPwd || !newPwd || !confirmPwd) {
            afficherAlerte('danger', 'Tous les champs sont requis.');
            return;
        }

        if (newPwd !== confirmPwd) {
            afficherAlerte('danger', 'Les nouveaux mots de passe ne correspondent pas.');
            return;
        }

        if (newPwd.length < 8) {
            afficherAlerte('danger', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
            return;
        }

        // Envoyer la requête POST
        fetch('/password/change', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                old_password: oldPwd,
                new_password: newPwd,
                new_password_confirmation: confirmPwd
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                afficherAlerte('success', data.message);
                // Rediriger vers login après 2 secondes
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                afficherAlerte('danger', data.message);
            }
        })
        .catch(error => {
            afficherAlerte('danger', 'Erreur de connexion. Veuillez réessayer.');
            console.error('Erreur:', error);
        });
    }

    function afficherAlerte(type, message) {
        const alertDiv = document.getElementById('passwordAlert');
        alertDiv.className = `alert alert-${type} d-block`;
        alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}`;
    }
    </script>

    @stack('scripts')
</body>
</html>
