{{--
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Charger les stats du dashboard
    chargerStatsDashboard();

    // Recharge automatique toutes les 30 secondes
    setInterval(chargerStatsDashboard, 30000);
});

function chargerStatsDashboard() {
    fetch("{{ route('admin.dashboard.stats') }}?periode=mois", {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Mettre à jour le chiffre d'affaires
            document.getElementById('chiffre-affaires').innerText =
                new Intl.NumberFormat('fr-FR').format(result.data.chiffre_affaires) + ' FCFA';

            // Commandes
            document.getElementById('total-commandes').innerText =
                result.data.commandes.total;

            // Autres stats si disponibles
            if (result.data.total_ventes) {
                document.getElementById('total-ventes').innerText = result.data.total_ventes;
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
</script> --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Commerciale - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Header avec logo amélioré */
       .sidebar-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 0.5rem 0.65rem;
            margin-bottom: 1rem;
            border-radius: 0 0 15px 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.15);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
        }

        .logo-text {
            color: white;
            text-align: left;
        }

        .logo-text h4 {
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }

        .logo-text small {
            opacity: 0.9;
            font-size: 0.85rem;
        }

        .sidebar {
            background: white;
            min-height: 100vh;
            box-shadow: var(--card-shadow);
            position: relative;
            z-index: 10;
        }

        .nav-link {
            color: #333;
            padding: 0.75rem 1rem;
            margin: 0.2rem 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover, .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white !important;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .badge-notification {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            margin-left: auto;
        }

        /* Cards principales avec design de l'image */
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            position: relative;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.border-primary::before { background: linear-gradient(90deg, #4361ee, #3a0ca3); }
        .stat-card.border-success::before { background: linear-gradient(90deg, #4cc9f0, #4895ef); }
        .stat-card.border-info::before { background: linear-gradient(90deg, #560bad, #3a0ca3); }
        .stat-card.border-warning::before { background: linear-gradient(90deg, #f72585, #b5179e); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-icon.primary {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
        }

        .stat-icon.warning {
            background: rgba(247, 37, 133, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.info {
            background: rgba(58, 12, 163, 0.1);
            color: var(--secondary-color);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0.5rem 0;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Section Performance Commerciaux - Design amélioré */
        .performance-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .performance-card .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .performance-card .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .commercial-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }

        .performance-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
            color: white;
        }

        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-custom .progress-bar {
            border-radius: 10px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        /* Section Activités - Design amélioré */
        .activity-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .activity-card .card-header {
            background: linear-gradient(135deg, #560bad, #3a0ca3);
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .activity-card .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .activity-item {
            padding: 1.2rem;
            border-left: 4px solid var(--primary-color);
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.05), transparent);
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .activity-item.new {
            border-left-color: var(--warning-color);
            background: linear-gradient(90deg, rgba(247, 37, 133, 0.1), transparent);
        }

        .activity-type-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        /* Header principal */
        .main-header {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        /* Table styling amélioré */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-custom thead th {
            border: none;
            background: transparent;
            color: #6c757d;
            font-weight: 600;
            padding: 1rem;
            font-size: 0.9rem;
            border-bottom: 2px solid #e9ecef;
        }

        .table-custom tbody tr {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .table-custom tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .table-custom tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
            border-top: none;
        }

        .table-custom tbody td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .table-custom tbody td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        /* Page Paramètres - Style inspiré de l'image */
        .settings-container {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
        }

        .settings-section {
            margin-bottom: 2.5rem;
        }

        .settings-section h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.1);
        }

        .settings-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .settings-list li {
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .settings-list li:last-child {
            border-bottom: none;
        }

        .settings-list li a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s ease;
        }

        .settings-list li a:hover {
            color: var(--primary-color);
        }

        .settings-list li a i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Thème selection */
        .theme-options {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .theme-card {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .theme-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .theme-card.active {
            border-color: var(--primary-color);
            background: rgba(67, 97, 238, 0.05);
        }

        .theme-card .theme-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .theme-card .theme-name {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .theme-card .theme-desc {
            font-size: 0.85rem;
            color: #666;
        }

        /* Toggle switches */
        .switch-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* Langue selection */
        .language-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .language-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .language-option:hover {
            border-color: var(--primary-color);
        }

        .language-option.active {
            border-color: var(--primary-color);
            background: rgba(67, 97, 238, 0.05);
        }

        .language-flag {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            object-fit: cover;
        }

        /* Section separator */
        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 2rem 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-number {
                font-size: 1.8rem;
            }

            .sidebar {
                min-height: auto;
            }

            .logo-container {
                flex-direction: column;
                text-align: center;
            }

            .main-header .btn-group {
                flex-wrap: wrap;
                margin-top: 1rem;
            }

            .theme-options {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <!-- En-tête amélioré avec logo -->
                <div class="sidebar-header">
                    <div class="logo-container">
                        <div class="logo-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="logo-text">
                            <h4>GestComm</h4>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="px-3">
                    <h6 class="sidebar-heading text-muted mb-3">
                        <small>MENU PRINCIPAL</small>
                    </h6>

                    <ul class="nav flex-column mb-5">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-page="dashboard">
                                <i class="bi bi-speedometer2"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="commandes">
                                <i class="bi bi-cart"></i>
                                Commandes
                                <span class="badge bg-danger badge-notification" id="commandes-badge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="ventes">
                                <i class="bi bi-cash-coin"></i>
                                Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="livraisons">
                                <i class="bi bi-truck"></i>
                                Livraisons
                                <span class="badge bg-warning badge-notification" id="livraisons-badge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="produits">
                                <i class="bi bi-box"></i>
                                Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="clients">
                                <i class="bi bi-people"></i>
                                Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="versements">
                                <i class="bi bi-wallet2"></i>
                                Versements
                                <span class="badge bg-warning badge-notification" id="versements-badge">0</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Section Analytique -->
                    <h6 class="sidebar-heading text-muted mb-3">
                        <small>ANALYTIQUE</small>
                    </h6>

                    <ul class="nav flex-column mb-5">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="rapports">
                                <i class="bi bi-graph-up"></i>
                                Rapports
                            </a>
                        </li>
                    </ul>

                    <!-- Section Administration -->
                    <h6 class="sidebar-heading text-muted mb-3">
                        <small>ADMINISTRATION</small>
                    </h6>

                    <ul class="nav flex-column mb-5">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="users">
                                <i class="bi bi-person-badge"></i>
                                Utilisateurs
                            </a>
                        </li>
                        <li class="nav-link" href="#" data-page="settings">
                                <i class="bi bi-gear"></i>
                                Paramètres
                            </a>
                        </li>
                    </ul>

                    <!-- Profil utilisateur -->
                    <div class="card border-0 bg-light mt-5">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="commercial-avatar me-3" id="user-avatar">
                                    A
                                </div>
                                                              </div>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4" id="main-content">
                <!-- Le contenu sera chargé dynamiquement ici -->
            </main>
        </div>
    </div>

    <!-- Modal Créer/Modifier Livraison -->
    <div class="modal fade" id="livraisonModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="livraisonModalTitle">Nouvelle Livraison</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="livraisonForm">
                        <input type="hidden" id="livraison_id" name="id">

                        <div class="row g-3">
                            <!-- Terrain -->
                            <div class="col-md-6">
                                <label for="terrain_id" class="form-label">Terrain <span class="text-danger">*</span></label>
                                <select class="form-select" id="terrain_id" name="terrain_id" required>
                                    <option value="">Sélectionner un terrain</option>
                                </select>
                            </div>

                            <!-- Chauffeur -->
                            <div class="col-md-6">
                                <label for="chauffeur_id" class="form-label">Chauffeur <span class="text-danger">*</span></label>
                                <select class="form-select" id="chauffeur_id" name="chauffeur_id" required>
                                    <option value="">Sélectionner un chauffeur</option>
                                </select>
                            </div>

                            <!-- Commande -->
                            <div class="col-md-12">
                                <label for="commande_id" class="form-label">Commande <span class="text-danger">*</span></label>
                                <select class="form-select" id="commande_id" name="commande_id" required>
                                    <option value="">Sélectionner une commande</option>
                                </select>
                            </div>

                            <!-- Date Livraison -->
                            <div class="col-md-6">
                                <label for="date_livraison" class="form-label">Date de Livraison <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="date_livraison" name="date_livraison" required>
                            </div>

                            <!-- Statut -->
                            <div class="col-md-6">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select" id="statut" name="statut">
                                    <option value="en_attente">En attente</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="livree">Livrée</option>
                                    <option value="annulee">Annulée</option>
                                </select>
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ajoutez des notes..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Annuler
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveLivraison()">
                        <i class="bi bi-check-circle me-1"></i>
                        <span id="saveLivraisonBtnText">Enregistrer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation Suppression -->
    <div class="modal fade" id="deleteLivraisonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Êtes-vous sûr de vouloir supprimer cette livraison ?
                    </p>
                    <p class="text-muted small">
                        Cette action est irréversible.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteLivraison()">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables globales
        let currentPage = 'dashboard';
        let livraisonsData = []; // Stockage local des livraisons

        // Données dynamiques depuis le backend
        const stats = @json($stats ?? []);
        const activities = @json($activities ?? []);
        const periode = '{{ $periode ?? "mois" }}';
        const newCount = {{ $newCount ?? 0 }};

        // Données mock pour le développement (à remplacer par des appels API)
        // const mockTerrains = [
        //     { id: 1, nom: 'Terrain Zone Nord' },
        //     { id: 2, nom: 'Terrain Zone Sud' },
        //     { id: 3, nom: 'Terrain Zone Est' }
        // ];

        // const mockChauffeurs = [
        //     { id: 1, nom: 'Jean Dupont' },
        //     { id: 2, nom: 'Marie Martin' },
        //     { id: 3, nom: 'Pierre Durand' }
        // ];

        // const mockCommandes = [
        //     { id: 1, numero: 'CMD-001', client: 'Client A' },
        //     { id: 2, numero: 'CMD-002', client: 'Client B' },
        //     { id: 3, numero: 'CMD-003', client: 'Client C' }
        // ];

        // // Initialisation des livraisons mock
        // livraisonsData = [
        //     {
        //         id: 1,
        //         commande_numero: 'CMD-001',
        //         client_nom: 'Client A',
        //         terrain_nom: 'Terrain Zone Nord',
        //         chauffeur_nom: 'Jean Dupont',
        //         date_livraison: '2024-02-10 14:00',
        //         statut: 'en_attente'
        //     },
        //     {
        //         id: 2,
        //         commande_numero: 'CMD-002',
        //         client_nom: 'Client B',
        //         terrain_nom: 'Terrain Zone Sud',
        //         chauffeur_nom: 'Marie Martin',
        //         date_livraison: '2024-02-11 10:30',
        //         statut: 'en_cours'
        //     }
        // ];

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboard();
            setupEventListeners();
            updateBadges();

            // Auto-refresh toutes les 5 minutes
            setInterval(() => {
                if (currentPage === 'dashboard') {
                    loadDashboard();
                }
            }, 300000);
        });

        function updateBadges() {
            // Commandes en attente
            const enAttente = stats.commandes?.en_attente || 0;
            document.getElementById('commandes-badge').textContent = enAttente;
            document.getElementById('commandes-badge').style.display = enAttente > 0 ? 'inline' : 'none';

            // Versements en attente
            const versementsEnAttente = stats.versements?.en_attente || 0;
            document.getElementById('versements-badge').textContent = versementsEnAttente;
            document.getElementById('versements-badge').style.display = versementsEnAttente > 0 ? 'inline' : 'none';

            // Livraisons en cours
            const livraisonsEnCours = livraisonsData.filter(l => l.statut === 'en_cours').length;
            const livraisonsBadge = document.getElementById('livraisons-badge');
            if (livraisonsBadge) {
                livraisonsBadge.textContent = livraisonsEnCours;
                livraisonsBadge.style.display = livraisonsEnCours > 0 ? 'inline' : 'none';
            }
        }

        function setupEventListeners() {
            // Navigation
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    if (page) {
                        navigateTo(page);
                    }
                });
            });

            // Logout
            document.getElementById('logout-btn').addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                    window.location.href = '/login';
                }
            });
        }

        function navigateTo(page) {
            // Mise à jour de la navigation active
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-page') === page) {
                    link.classList.add('active');
                }
            });

            currentPage = page;

            // Charger la page appropriée
            switch(page) {
                case 'dashboard':
                    loadDashboard();
                    break;
                case 'settings':
                    loadSettings();
                    break;
                case 'livraisons':
                    loadLivraisons();
                    break;
                default:
                    loadPlaceholderPage(page);
                    break;
            }
        }

        function loadPlaceholderPage(page) {
            document.getElementById('main-content').innerHTML = `
                <div class="main-header">
                    <h1 class="h3 fw-bold mb-1">
                        <i class="bi bi-gear text-primary me-2"></i>
                        ${capitalizeFirstLetter(page)}
                    </h1>
                    <p class="text-muted mb-0">Page en cours de développement</p>
                </div>
                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle me-2"></i>
                    La fonctionnalité "${capitalizeFirstLetter(page)}" sera bientôt disponible.
                </div>
            `;
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // ========== DASHBOARD ==========
        function loadDashboard() {
            const newCount = activities.filter(a => a.status === 'pending').length;

            document.getElementById('main-content').innerHTML = `
                <!-- Header avec titre et filtres -->
                <div class="main-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 fw-bold mb-1">
                                <i class="bi bi-speedometer2 text-primary me-2"></i>
                                Tableau de bord
                            </h1>
                            <p class="text-muted mb-0">Vue d'ensemble de votre activité commerciale</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <!-- Chiffre d'affaires -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-primary h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-icon primary">
                                            <i class="bi bi-currency-exchange"></i>
                                        </div>
                                        <p class="text-muted mb-1">CHIFFRE D'AFFAIRES</p>
                                        <h2 class="stat-number">
                                            ${(stats.chiffre_affaires || 0).toLocaleString('fr-FR')}
                                            <small class="fs-6 text-muted">FCFA</small>
                                        </h2>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event"></i>
                                            ${getPeriodeLabel(periode)}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commandes -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-success h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-icon success">
                                            <i class="bi bi-cart-check"></i>
                                        </div>
                                        <p class="text-muted mb-1">COMMANDES</p>
                                        <div class="d-flex align-items-center">
                                            <h2 class="stat-number me-3">${stats.commandes?.total || 0}</h2>
                                            <div>
                                                <span class="badge bg-success">${stats.commandes?.livrees || 0} livrées</span>
                                                ${stats.commandes?.en_attente > 0
                                                    ? `<span class="badge bg-warning d-block mt-1">${stats.commandes?.en_attente || 0} en attente</span>`
                                                    : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ventes -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-info h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-icon info">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                        <p class="text-muted mb-1">VENTES</p>
                                        <h2 class="stat-number">${stats.total_ventes || 0}</h2>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Transactions</small>
                                            <small class="text-muted">${stats.total_quantite || 0} unités</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Versements -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-warning h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-icon warning">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <p class="text-muted mb-1">VERSEMENTS</p>
                                        <h2 class="stat-number">
                                            ${((stats.versements?.valides || 0) + (stats.versements?.en_attente || 0)).toLocaleString('fr-FR')}
                                        </h2>
                                        <div class="d-flex justify-content-between">
                                            <span class="badge bg-success">${(stats.versements?.valides || 0).toLocaleString('fr-FR')} validés</span>
                                            ${stats.versements?.en_attente > 0
                                                ? `<span class="badge bg-warning">${(stats.versements?.en_attente || 0).toLocaleString('fr-FR')} en attente</span>`
                                                : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Performance Commerciaux -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="performance-card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-trophy me-2"></i>
                                        Performance des commerciaux
                                    </h5>
                                    <a href="#" class="btn btn-sm btn-light" onclick="navigateTo('rapports')">
                                        Voir plus <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                ${stats.performance_commerciaux && stats.performance_commerciaux.length > 0 ? `
                                    <div class="table-responsive">
                                        <table class="table table-custom">
                                            <thead>
                                                <tr>
                                                    <th>Commercial</th>
                                                    <th>Ventes</th>
                                                    <th>Quantité</th>
                                                    <th>Commandes</th>
                                                    <th>Performance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${stats.performance_commerciaux.map(commercial => {
                                                    const totalVentes = commercial.total_ventes || 0;
                                                    const totalCommandes = commercial.total_commandes || 0;
                                                    const objectif = commercial.objectif || (commercial.total_commandes * 1.2 || 100000);
                                                    const performance = Math.min(100, (totalVentes / Math.max(1, objectif)) * 100);

                                                    return `
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="commercial-avatar me-3">
                                                                        ${commercial.nom ? commercial.nom.charAt(0).toUpperCase() : 'C'}
                                                                    </div>
                                                                    <div>
                                                                        <strong>${commercial.nom || 'Non défini'}</strong>
                                                                        <div class="text-muted small">${commercial.role || 'Commercial'}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <strong class="text-primary">
                                                                    ${totalVentes.toLocaleString('fr-FR')}
                                                                </strong>
                                                                <small class="text-muted d-block">FCFA</small>
                                                            </td>
                                                            <td>
                                                                <span class="performance-badge">${commercial.total_quantite_vendue || 0}</span>
                                                            </td>
                                                            <td>
                                                                <strong class="text-success">
                                                                    ${totalCommandes.toLocaleString('fr-FR')}
                                                                </strong>
                                                                <small class="text-muted d-block">FCFA</small>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress-custom flex-grow-1 me-2">
                                                                        <div class="progress-bar" style="width: ${performance}%"></div>
                                                                    </div>
                                                                    <span class="fw-bold">${performance.toFixed(1)}%</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    `;
                                                }).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                ` : `
                                    <div class="text-center py-5">
                                        <i class="bi bi-bar-chart text-muted fs-1"></i>
                                        <p class="text-muted mt-3">Aucune donnée de performance disponible</p>
                                        <p class="text-muted small">Les données apparaîtront lorsque les commerciaux auront effectué des ventes</p>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Activités -->
                <div class="row g-4">
                    <div class="col-12">
                        <div class="activity-card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-activity me-2"></i>
                                        Activités récentes des commerciaux
                                        ${newCount > 0 ? `<span class="badge bg-danger ms-2">${newCount} nouvelles</span>` : ''}
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                ${activities && activities.length > 0 ? activities.map(activity => {
                                    const isNew = activity.status === 'pending' ? 'new' : '';
                                    const timeAgo = getTimeAgo(new Date(activity.created_at));
                                    const userData = activity.user || { name: 'Utilisateur' };

                                    return `
                                        <div class="activity-item ${isNew}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="d-flex align-items-center">
                                                    <div class="commercial-avatar me-3">
                                                        ${userData.name ? userData.name.charAt(0).toUpperCase() : 'U'}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">${userData.name || 'Utilisateur'}</h6>
                                                        <div class="d-flex align-items-center">
                                                            <span class="activity-type-badge me-2">${(activity.type || 'ACTIVITÉ').toUpperCase()}</span>
                                                            <small class="text-muted">
                                                                <i class="bi bi-clock me-1"></i>
                                                                ${timeAgo}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                ${activity.status === 'pending' ? `<span class="badge bg-danger">NOUVEAU</span>` : ''}
                                            </div>
                                            <div class="mt-3">
                                                ${activity.data ? `
                                                    <div class="card bg-light border-0">
                                                        <div class="card-body p-3">
                                                            <pre class="mb-0 small text-muted" style="white-space: pre-wrap;">${formatActivityData(activity.data)}</pre>
                                                        </div>
                                                    </div>
                                                ` : ''}
                                            </div>
                                            ${activity.status === 'pending' ? `
                                                <div class="d-flex gap-2 mt-3">
                                                    <button class="btn btn-success btn-sm px-3" onclick="validateActivity(${activity.id})">
                                                        <i class="bi bi-check-circle me-1"></i> Valider
                                                    </button>
                                                    <button class="btn btn-danger btn-sm px-3" onclick="rejectActivity(${activity.id})">
                                                        <i class="bi bi-x-circle me-1"></i> Refuser
                                                    </button>
                                                </div>
                                            ` : `
                                                <div class="mt-3">
                                                    <span class="badge bg-${activity.status === 'validated' ? 'success' : 'secondary'}">
                                                        ${activity.status === 'validated' ? 'Validé' : 'Traité'}
                                                    </span>
                                                    <small class="text-muted ms-2">
                                                        Traité le ${formatDate(activity.updated_at || activity.created_at)}
                                                    </small>
                                                </div>
                                            `}
                                        </div>
                                    `;
                                }).join('') : `
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox text-muted fs-1"></i>
                                        <p class="text-muted mt-3">Aucune activité récente</p>
                                        <p class="text-muted small">Les activités des commerciaux apparaîtront ici</p>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // ========== LIVRAISONS CRUD ==========
        function loadLivraisons() {
            // Calculer les stats
            const totalLivraisons = livraisonsData.length;
            const enAttente = livraisonsData.filter(l => l.statut === 'en_attente').length;
            const enCours = livraisonsData.filter(l => l.statut === 'en_cours').length;
            const livrees = livraisonsData.filter(l => l.statut === 'livree').length;

            document.getElementById('main-content').innerHTML = `
                <!-- Header Livraisons -->
                <div class="main-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 fw-bold mb-1">
                                <i class="bi bi-truck text-primary me-2"></i>
                                Gestion des Livraisons
                            </h1>
                            <p class="text-muted mb-0">Programmez et suivez toutes vos livraisons</p>
                        </div>
                        <button class="btn btn-primary" onclick="openCreateLivraisonModal()">
                            <i class="bi bi-plus-circle me-1"></i>
                            Nouvelle Livraison
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-primary">
                            <div class="card-body p-4">
                                <div class="stat-icon primary">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <p class="text-muted mb-1">TOTAL</p>
                                <h2 class="stat-number">${totalLivraisons}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-warning">
                            <div class="card-body p-4">
                                <div class="stat-icon warning">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <p class="text-muted mb-1">EN ATTENTE</p>
                                <h2 class="stat-number">${enAttente}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-info">
                            <div class="card-body p-4">
                                <div class="stat-icon info">
                                    <i class="bi bi-truck-flatbed"></i>
                                </div>
                                <p class="text-muted mb-1">EN COURS</p>
                                <h2 class="stat-number">${enCours}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card border-success">
                            <div class="card-body p-4">
                                <div class="stat-icon success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <p class="text-muted mb-1">LIVRÉES</p>
                                <h2 class="stat-number">${livrees}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Livraisons -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Liste des Livraisons</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Commande</th>
                                        <th>Terrain</th>
                                        <th>Chauffeur</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="livraisons-table-body">
                                    ${renderLivraisonsTable()}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            updateBadges();
        }

        function renderLivraisonsTable() {
            if (livraisonsData.length === 0) {
                return `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-muted fs-1"></i>
                            <p class="text-muted mt-3">Aucune livraison enregistrée</p>
                            <button class="btn btn-primary mt-3" onclick="openCreateLivraisonModal()">
                                <i class="bi bi-plus-circle me-1"></i>
                                Ajouter une livraison
                            </button>
                        </td>
                    </tr>
                `;
            }

            return livraisonsData.map(livraison => {
                const statutBadges = {
                    'en_attente': 'warning',
                    'en_cours': 'info',
                    'livree': 'success',
                    'annulee': 'danger'
                };

                const statutLabels = {
                    'en_attente': 'En attente',
                    'en_cours': 'En cours',
                    'livree': 'Livrée',
                    'annulee': 'Annulée'
                };

                const statutBadge = statutBadges[livraison.statut] || 'secondary';
                const statutLabel = statutLabels[livraison.statut] || livraison.statut;

                return `
                    <tr>
                        <td>
                            <strong>${livraison.commande_numero}</strong>
                            <div class="text-muted small">${livraison.client_nom}</div>
                        </td>
                        <td>${livraison.terrain_nom}</td>
                        <td>${livraison.chauffeur_nom}</td>
                        <td>${formatDateLivraison(livraison.date_livraison)}</td>
                        <td>
                            <span class="badge bg-${statutBadge}">${statutLabel}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary me-1" onclick="openEditLivraisonModal(${livraison.id})" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="openDeleteLivraisonModal(${livraison.id})" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openCreateLivraisonModal() {
            // Réinitialiser le formulaire
            document.getElementById('livraisonForm').reset();
            document.getElementById('livraison_id').value = '';
            document.getElementById('livraisonModalTitle').textContent = 'Nouvelle Livraison';
            document.getElementById('saveLivraisonBtnText').textContent = 'Enregistrer';

            // Charger les options
            loadTerrainOptions();
            loadChauffeurOptions();
            loadCommandeOptions();

            // Définir la date par défaut à maintenant
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('date_livraison').value = now.toISOString().slice(0, 16);

            // Ouvrir la modale
            const modal = new bootstrap.Modal(document.getElementById('livraisonModal'));
            modal.show();
        }

        function openEditLivraisonModal(id) {
            const livraison = livraisonsData.find(l => l.id === id);
            if (!livraison) {
                alert('Livraison non trouvée');
                return;
            }

            // Remplir le formulaire
            document.getElementById('livraison_id').value = livraison.id;
            document.getElementById('livraisonModalTitle').textContent = 'Modifier la Livraison';
            document.getElementById('saveLivraisonBtnText').textContent = 'Mettre à jour';

            // Charger les options
            loadTerrainOptions();
            loadChauffeurOptions();
            loadCommandeOptions();

            // Définir les valeurs (simplifié pour la démo)
            setTimeout(() => {
                // Sélectionner les valeurs appropriées dans les dropdowns
                document.getElementById('statut').value = livraison.statut;

                // Formater la date pour l'input datetime-local
                if (livraison.date_livraison) {
                    const date = new Date(livraison.date_livraison);
                    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
                    document.getElementById('date_livraison').value = date.toISOString().slice(0, 16);
                }
            }, 100);

            // Ouvrir la modale
            const modal = new bootstrap.Modal(document.getElementById('livraisonModal'));
            modal.show();
        }

        function saveLivraison() {
            const form = document.getElementById('livraisonForm');

            // Validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const id = document.getElementById('livraison_id').value;
            const terrainId = parseInt(document.getElementById('terrain_id').value);
            const chauffeurId = parseInt(document.getElementById('chauffeur_id').value);
            const commandeId = parseInt(document.getElementById('commande_id').value);

            const terrain = mockTerrains.find(t => t.id === terrainId);
            const chauffeur = mockChauffeurs.find(c => c.id === chauffeurId);
            const commande = mockCommandes.find(c => c.id === commandeId);

            const newLivraison = {
                id: id ? parseInt(id) : livraisonsData.length + 1,
                commande_numero: commande ? commande.numero : 'CMD-' + (livraisonsData.length + 1),
                client_nom: commande ? commande.client : 'Client inconnu',
                terrain_nom: terrain ? terrain.nom : 'Terrain inconnu',
                chauffeur_nom: chauffeur ? chauffeur.nom : 'Chauffeur inconnu',
                date_livraison: document.getElementById('date_livraison').value,
                statut: document.getElementById('statut').value,
                notes: document.getElementById('notes').value
            };

            if (id) {
                // Mise à jour
                const index = livraisonsData.findIndex(l => l.id === parseInt(id));
                if (index !== -1) {
                    livraisonsData[index] = newLivraison;
                }
            } else {
                // Création
                livraisonsData.push(newLivraison);
            }

            // Fermer la modale
            const modal = bootstrap.Modal.getInstance(document.getElementById('livraisonModal'));
            modal.hide();

            // Recharger la page
            loadLivraisons();

            // Notification de succès
            alert(id ? 'Livraison modifiée avec succès !' : 'Livraison créée avec succès !');
        }

        function openDeleteLivraisonModal(id) {
            window.currentLivraisonId = id;
            const modal = new bootstrap.Modal(document.getElementById('deleteLivraisonModal'));
            modal.show();
        }

        function confirmDeleteLivraison() {
            const id = window.currentLivraisonId;
            const index = livraisonsData.findIndex(l => l.id === id);

            if (index !== -1) {
                livraisonsData.splice(index, 1);
            }

            // Fermer la modale
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteLivraisonModal'));
            modal.hide();

            // Recharger la page
            loadLivraisons();

            alert('Livraison supprimée avec succès !');
        }

        // Fonctions pour charger les options des dropdowns
        function loadTerrainOptions() {
            const select = document.getElementById('terrain_id');
            select.innerHTML = '<option value="">Sélectionner un terrain</option>';

            mockTerrains.forEach(terrain => {
                const option = document.createElement('option');
                option.value = terrain.id;
                option.textContent = terrain.nom;
                select.appendChild(option);
            });
        }

        function loadChauffeurOptions() {
            const select = document.getElementById('chauffeur_id');
            select.innerHTML = '<option value="">Sélectionner un chauffeur</option>';

            mockChauffeurs.forEach(chauffeur => {
                const option = document.createElement('option');
                option.value = chauffeur.id;
                option.textContent = chauffeur.nom;
                select.appendChild(option);
            });
        }

        function loadCommandeOptions() {
            const select = document.getElementById('commande_id');
            select.innerHTML = '<option value="">Sélectionner une commande</option>';

            mockCommandes.forEach(commande => {
                const option = document.createElement('option');
                option.value = commande.id;
                option.textContent = `${commande.numero} - ${commande.client}`;
                select.appendChild(option);
            });
        }

function loadDashboard() {
    const dashboardPage = document.getElementById('dashboard-page');
    if (!dashboardPage) return;

const content = `
                <div class="main-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 fw-bold mb-1">
                    Tableau de bord Administrateur
                            </h1>
                        </div>

            <!-- Menu Utilisateur - Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <h3 id="user-name">Admin</h3>
                    <i class="bi bi-person-circle fs-4"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li class="px-3 py-2 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                            <div>
                                <h6 class="mb-0 fw-semibold" id="user-name">Admin</h6>
                                <small class="text-muted">Connecté</small>
                    </div>
                </div>

                <!-- Contenu des paramètres -->
                <div class="settings-container">
                    <!-- Section Générale -->
                    <div class="settings-section">
                        <h2>Général</h2>
                        <ul class="settings-list">
                            <li>
                                <a href="#">
                                    <i class="bi bi-calendar-week"></i>
                                    Programmation
                                </a>
                            </li>
                    <li><hr class="dropdown-divider m-0"></li>
                            <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i> Mon Profil
                                </a>
                            </li>
                            <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-lock me-2"></i> Changer Mot de Passe
                                </a>
                            </li>
                    <li><hr class="dropdown-divider m-0"></li>
                            <li>
                        <form method="POST" action="/logout" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
            </div>
        </div>
        </div>



    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-primary h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon primary">
                                <i class="bi bi-currency-exchange"></i>
                            </div>
                            <p class="text-muted mb-1">CHIFFRE D'AFFAIRES</p>
                            <h2 class="stat-number">
                                ${(stats.chiffre_affaires || 0).toLocaleString('fr-FR')}
                                <small class="fs-6 text-muted">FCFA</small>
                            </h2>
                            </div>
                            <div class="language-option" data-lang="es">
                                <img src="https://flagcdn.com/w40/es.png" class="language-flag" alt="Español">
                                <span>Español</span>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Thème -->
                    <div class="settings-section">
                        <h2>Thème</h2>
                        <p class="text-muted mb-3">Choisissez l'apparence de votre GestComm :</p>

                        <div class="theme-options">
                            <div class="theme-card active" data-theme="clair">
                                <div class="theme-icon text-warning">
                                    <i class="bi bi-sun"></i>
                                </div>
                                <div class="theme-name">Clair</div>
                                <div class="theme-desc">Interface lumineuse</div>
                            </div>

                            <div class="theme-card" data-theme="sombre">
                                <div class="theme-icon text-secondary">
                                    <i class="bi bi-moon"></i>
                                </div>
                                <div class="theme-name">Sombre</div>
                                <div class="theme-desc">Interface foncée</div>
                            </div>

                            <div class="theme-card" data-theme="auto">
                                <div class="theme-icon text-primary">
                                    <i class="bi bi-circle-half"></i>
                                </div>
                                <div class="theme-name">Automatique</div>
                                <div class="theme-desc">Suit votre système</div>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Paramètres avancés -->
                    <div class="settings-section">
                        <h2>Paramètres avancés</h2>

                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="ignore-auth" checked>
                                <span class="slider"></span>
                            </label>
                            <span>Ignorer la demande d'autorisation lors du démarrage</span>
                        </div>

                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="system-icon" checked>
                                <span class="slider"></span>
                            </label>
                            <span>Afficher l'icône dans la barre d'état système</span>
                        </div>
                    </div>

                    <!-- Paramètres spécifiques à votre système de gestion -->
                    <div class="settings-section">
                        <h2>Configuration commerciale</h2>

                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="email-notif" checked>
                                <span class="slider"></span>
                            </label>
                            <span>Notifications par email pour nouvelles commandes</span>
                        </div>

                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="approval-required">
                                <span class="slider"></span>
                            </label>
                            <span>Approbation requise pour commandes > 500.000 FCFA</span>
                        </div>

                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="stock-alerts" checked>
                                <span class="slider"></span>
                            </label>
                            <span>Alertes stock bas automatiques</span>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <button class="btn btn-outline-secondary" onclick="resetSettings()">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                            Restaurer les paramètres par défaut
                        </button>
                        <div>
                            <button class="btn btn-outline-danger me-2" onclick="cancelSettings()">
                                <i class="bi bi-x-circle me-2"></i>
                                Annuler
                            </button>
                            <button class="btn btn-primary" onclick="saveSettings()">
                                <i class="bi bi-check-circle me-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </div>
            `;

            setupSettingsEvents();
        }

        function setupSettingsEvents() {
            // Gestion des thèmes
            document.querySelectorAll('.theme-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Gestion des langues
            document.querySelectorAll('.language-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.language-option').forEach(o => o.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }

        function resetSettings() {
            if (confirm('Voulez-vous vraiment restaurer tous les paramètres par défaut ?')) {
                document.querySelectorAll('.switch input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = true;
                });

                document.querySelectorAll('.theme-card').forEach(card => card.classList.remove('active'));
                document.querySelector('.theme-card[data-theme="clair"]').classList.add('active');

                document.querySelectorAll('.language-option').forEach(option => option.classList.remove('active'));
                document.querySelector('.language-option[data-lang="fr"]').classList.add('active');

                alert('Paramètres restaurés avec succès !');
            }
        }

        function cancelSettings() {
            if (confirm('Annuler toutes les modifications non enregistrées ?')) {
                navigateTo('dashboard');
            }
        }

        function saveSettings() {
            const settings = {
                theme: document.querySelector('.theme-card.active').getAttribute('data-theme'),
                language: document.querySelector('.language-option.active').getAttribute('data-lang'),
                ignoreAuth: document.getElementById('ignore-auth').checked,
                systemIcon: document.getElementById('system-icon').checked,
                emailNotifications: document.getElementById('email-notif').checked,
                approvalRequired: document.getElementById('approval-required').checked,
                stockAlerts: document.getElementById('stock-alerts').checked
            };

            console.log('Paramètres sauvegardés:', settings);

            if (settings.theme === 'sombre') {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }

            alert('Paramètres enregistrés avec succès !');
            navigateTo('dashboard');
        }

        // ========== FONCTIONS UTILITAIRES ==========
        function formatActivityData(data) {
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    return data;
                }
            }
            return JSON.stringify(data, null, 2);
        }

        function getPeriodeLabel(periode) {
            const labels = {
                'jour': 'Aujourd\'hui',
                'semaine': 'Cette semaine',
                'mois': 'Ce mois',
                'annee': 'Cette année'
            };
            return labels[periode] || 'Ce mois';
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);

            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) return `il y a ${interval} an${interval > 1 ? 's' : ''}`;

            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return `il y a ${interval} mois`;

            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return `il y a ${interval} jour${interval > 1 ? 's' : ''}`;

            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return `il y a ${interval} heure${interval > 1 ? 's' : ''}`;

            interval = Math.floor(seconds / 60);
            if (interval >= 1) return `il y a ${interval} minute${interval > 1 ? 's' : ''}`;

            return 'à l\'instant';
        }

        function formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatDateLivraison(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function validateActivity(id) {
            if (confirm('Valider cette activité ?')) {
                alert(`Activité ${id} validée !`);
                loadDashboard();
            }
        }

        function rejectActivity(id) {
            if (confirm('Refuser cette activité ?')) {
                alert(`Activité ${id} refusée !`);
                loadDashboard();
            }
        }

        // Notifications au chargement
        document.addEventListener('DOMContentLoaded', function() {
            if (newCount > 0 && Notification.permission === "granted") {
                new Notification("Nouvelles activités", {
                    body: `${newCount} nouvelle(s) activité(s) nécessite(nt) votre attention`,
                    icon: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23f72585'><path d='M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z'/></svg>"
                });
            }
        });
    </script>

</body>
</html>
