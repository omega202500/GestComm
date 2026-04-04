@php
    function t($key, $replace = []) {
        return __($key, $replace);
    }
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

      /* ========== THÈME SOMBRE INSPIRÉ DE CLAUDE ========== */
        body.dark-theme {
        background-color: #1e1e2f;
        color: #e4e4e7;
        }
        /* Pour les textes dans la table des livraisons */
        body.dark-theme .table-custom tbody td,
        body.dark-theme .table-custom tbody td strong,
        body.dark-theme .table-custom tbody td .text-muted {
            color: #ffffff !important;
        }

        /* Pour les badges et les boutons */
        body.dark-theme .table-custom tbody td .badge {
            color: #ffffff;
        }

        /* Pour les liens d'action */
        body.dark-theme .table-custom tbody td .btn-primary,
        body.dark-theme .table-custom tbody td .btn-danger {
            color: #ffffff;
        }

        /* Sidebar sombre */
        body.dark-theme .sidebar {
        background-color: #18181b;
        border-right: 1px solid #2a2a2e;
        }

        body.dark-theme .sidebar-header {
        background: linear-gradient(135deg, #2d2d35, #1a1a1f);
        border-bottom: 1px solid #3a3a42;
        }

       /* Styles spécifiques pour les tableaux des utilisateurs en mode sombre */
        body.dark-theme .table-custom tbody td,
        body.dark-theme .table-custom tbody td strong,
        body.dark-theme .table-custom tbody td span,
        body.dark-theme .table-custom tbody td a,
        body.dark-theme .table-custom tbody td .text-muted {
            color: #e4e4e7 !important;
        }

        body.dark-theme .table-custom tbody td .badge {
            color: #ffffff !important;
        }

        body.dark-theme .table-custom tbody td .badge.bg-warning {
            color: #1a1a1a !important;
        }

        body.dark-theme .table-custom tbody tr:hover td,
        body.dark-theme .table-custom tbody tr:hover td strong {
            color: #ffffff !important;
        }

        /* Styles pour les cellules de tableau en mode sombre */
        body.dark-theme .table-custom td {
            background-color: #25252b;
            color: #e4e4e7 !important;
        }

        body.dark-theme .table-custom td strong {
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Styles pour les en-têtes de tableau */
        body.dark-theme .table-custom th {
            color: #a1a1aa !important;
            background-color: #1e1e2a;
            border-bottom-color: #3a3a44 !important;
        }

        /* Styles pour les champs spécifiques */
        body.dark-theme .table-custom td:first-child,
        body.dark-theme .table-custom td:nth-child(2),
        body.dark-theme .table-custom td:nth-child(3),
        body.dark-theme .table-custom td:nth-child(4),
        body.dark-theme .table-custom td:nth-child(5),
        body.dark-theme .table-custom td:nth-child(6),
        body.dark-theme .table-custom td:nth-child(7),
        body.dark-theme .table-custom td:nth-child(8) {
            color: #e4e4e7 !important;
        }

        /* Styles pour les emails et téléphones */
        body.dark-theme .table-custom td a[href^="mailto"],
        body.dark-theme .table-custom td a[href^="tel"] {
            color: #818cf8 !important;
        }

        body.dark-theme .table-custom td a[href^="mailto"]:hover,
        body.dark-theme .table-custom td a[href^="tel"]:hover {
            color: #a78bfa !important;
        }

        /* Navigation */
        body.dark-theme .nav-link {
        color: #a1a1aa;
        }

        body.dark-theme .nav-link:hover {
        background: linear-gradient(90deg, #3b3b44, #2a2a32);
        color: #ffffff !important;
        }

        body.dark-theme .nav-link.active {
        background: linear-gradient(90deg, #4f46e5, #6366f1);
        color: white !important;
        }

        /* Cartes statistiques */
        body.dark-theme .stat-card,
        body.dark-theme .card,
        body.dark-theme .main-header,
        body.dark-theme .settings-container,
        body.dark-theme .performance-card,
        body.dark-theme .activity-card {
        background-color: #25252b;
        border: 1px solid #2f2f36;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        body.dark-theme .stat-card::before {
        background: linear-gradient(90deg, #4f46e5, #818cf8);
        }

        /* En-tête des cartes */
        body.dark-theme .card-header,
        body.dark-theme .performance-card .card-header,
        body.dark-theme .activity-card .card-header {
        background: linear-gradient(135deg, #2d2d35, #1f1f26);
        border-bottom: 1px solid #3a3a44;
        }

        /* Tableaux */
        body.dark-theme .table-custom tbody tr {
        background-color: #25252b;
        border-bottom: 1px solid #2f2f36;
        }

        body.dark-theme .table-custom tbody tr:hover {
        background-color: #2d2d35;
        transform: translateY(-2px);
        }

        body.dark-theme .table-custom thead th {
        color: #a1a1aa;
        border-bottom: 1px solid #3a3a44;
        }

        /* Activités */
        body.dark-theme .activity-item {
        background: linear-gradient(90deg, rgba(79, 70, 229, 0.08), rgba(0, 0, 0, 0.2));
        border-left-color: #4f46e5;
        }

        body.dark-theme .activity-item:hover {
        background: linear-gradient(90deg, rgba(79, 70, 229, 0.12), rgba(0, 0, 0, 0.25));
        }

        body.dark-theme .activity-item.new {
        border-left-color: #f97316;
        background: linear-gradient(90deg, rgba(249, 115, 22, 0.08), rgba(0, 0, 0, 0.2));
        }

        /* Badges */
        body.dark-theme .badge.bg-success {
        background-color: #22c55e !important;
        }

        body.dark-theme .badge.bg-warning {
        background-color: #f97316 !important;
        color: #1a1a1a;
        }

        body.dark-theme .badge.bg-danger {
        background-color: #ef4444 !important;
        }

        body.dark-theme .badge.bg-info {
        background-color: #06b6d4 !important;
        }

        /* Boutons */
        body.dark-theme .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        }

        body.dark-theme .btn-primary:hover {
        background: linear-gradient(135deg, #6366f1, #818cf8);
        transform: translateY(-1px);
        }

        body.dark-theme .btn-secondary {
        background-color: #3a3a44;
        border-color: #4a4a55;
        color: #e4e4e7;
        }

        body.dark-theme .btn-secondary:hover {
        background-color: #4a4a55;
        border-color: #5a5a66;
        }

        body.dark-theme .btn-outline-secondary {
        color: #a1a1aa;
        border-color: #4a4a55;
        }

        body.dark-theme .btn-outline-secondary:hover {
        background-color: #3a3a44;
        color: #ffffff;
        }

        /* Modales */
        body.dark-theme .modal-content {
        background-color: #25252b;
        border: 1px solid #3a3a44;
        }

        body.dark-theme .modal-header {
        border-bottom-color: #3a3a44;
        background: linear-gradient(135deg, #2d2d35, #1f1f26);
        }

        body.dark-theme .modal-footer {
        border-top-color: #3a3a44;
        }

        /* Formulaires */
        body.dark-theme .form-control,
        body.dark-theme .form-select {
        background-color: #1e1e2a;
        border-color: #3a3a44;
        color: #e4e4e7;
        }

        body.dark-theme .form-control:focus,
        body.dark-theme .form-select:focus {
        background-color: #252530;
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }

        body.dark-theme .form-label {
        color: #d4d4d8;
        }

        /* Dropdown */
        body.dark-theme .dropdown-menu {
        background-color: #25252b;
        border: 1px solid #3a3a44;
        }

        body.dark-theme .dropdown-item {
        color: #e4e4e7;
        }

        body.dark-theme .dropdown-item:hover {
        background-color: #3a3a44;
        color: #ffffff;
        }

        body.dark-theme .dropdown-divider {
        border-top-color: #3a3a44;
        }

        body.dark-theme .bg-light {
        background-color: #1e1e2a !important;
        }

        /* Textes */
        body.dark-theme .text-muted {
        color: #a1a1aa !important;
        }

        body.dark-theme .text-primary {
        color: #818cf8 !important;
        }

        body.dark-theme .text-success {
        color: #4ade80 !important;
        }

        body.dark-theme .text-warning {
        color: #fb923c !important;
        }

        body.dark-theme .text-danger {
        color: #f87171 !important;
        }

        body.dark-theme .text-info {
        color: #22d3ee !important;
        }

        /* Statistiques - gradient des nombres */
        body.dark-theme .stat-number {
        background: linear-gradient(90deg, #a78bfa, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        }

        /* Avatars commerciaux */
        body.dark-theme .commercial-avatar {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        body.dark-theme .performance-badge {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        }

        /* Progress bar */
        body.dark-theme .progress-custom {
        background-color: #2f2f36;
        }

        body.dark-theme .progress-custom .progress-bar {
        background: linear-gradient(90deg, #4f46e5, #818cf8);
        }

        /* Switch */
        body.dark-theme .slider {
        background-color: #3a3a44;
        }

        body.dark-theme input:checked + .slider {
        background-color: #4f46e5;
        }

        /* Theme cards */
        body.dark-theme .theme-card {
        border-color: #3a3a44;
        background-color: #1e1e2a;
        }

        body.dark-theme .theme-card:hover {
        border-color: #4f46e5;
        }

        body.dark-theme .theme-card.active {
        border-color: #4f46e5;
        background: rgba(79, 70, 229, 0.1);
        }

        body.dark-theme .theme-card .theme-desc {
        color: #a1a1aa;
        }

        /* Section séparateurs */
        body.dark-theme .section-divider {
        background: linear-gradient(to right, transparent, #3a3a44, transparent);
        }

        /* Alertes */
        body.dark-theme .alert-info {
        background-color: rgba(6, 182, 212, 0.1);
        border-color: #06b6d4;
        color: #a5f3fc;
        }

        body.dark-theme .alert-success {
        background-color: rgba(34, 197, 94, 0.1);
        border-color: #22c55e;
        color: #86efac;
        }

        body.dark-theme .alert-danger {
        background-color: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
        color: #fecaca;
        }

        /* Toast notifications */
        body.dark-theme .toast {
        background-color: #25252b;
        border: 1px solid #3a3a44;
        }

        body.dark-theme .toast-header {
        background-color: #1e1e2a;
        border-bottom-color: #3a3a44;
        color: #e4e4e7;
        }

        /* Scrollbar personnalisée */
        body.dark-theme ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
        }

        body.dark-theme ::-webkit-scrollbar-track {
        background: #1e1e2a;
        border-radius: 4px;
        }

        body.dark-theme ::-webkit-scrollbar-thumb {
        background: #4a4a55;
        border-radius: 4px;
        }

        body.dark-theme ::-webkit-scrollbar-thumb:hover {
        background: #6366f1;
        }

        /* Liens */
        body.dark-theme a:not(.nav-link):not(.dropdown-item) {
        color: #818cf8;
        }

        body.dark-theme a:not(.nav-link):not(.dropdown-item):hover {
        color: #a78bfa;
        }

        /* Styles pour les onglets et la page utilisateurs */
        body.dark-theme .nav-tabs {
            border-bottom-color: #3a3a44;
        }

        body.dark-theme .nav-tabs .nav-link {
            color: #a1a1aa;
            background-color: transparent;
            border-color: transparent;
        }

        body.dark-theme .nav-tabs .nav-link:hover {
            border-color: #4a4a55;
            color: #ffffff;
        }

        body.dark-theme .nav-tabs .nav-link.active {
            color: #818cf8;
            background-color: #25252b;
            border-color: #4f46e5 #4f46e5 #25252b;
        }

        body.dark-theme .card {
            background-color: #25252b;
            border: 1px solid #2f2f36;
        }

        body.dark-theme .card-header {
            background: linear-gradient(135deg, #2d2d35, #1f1f26);
            border-bottom: 1px solid #3a3a44;
        }

        body.dark-theme .card-header h5 {
            color: #ffffff;
        }

        body.dark-theme .table-custom thead th {
            color: #a1a1aa;
            border-bottom: 1px solid #3a3a44;
        }

        body.dark-theme .table-custom tbody td {
            color: #e4e4e7;
            border-bottom: 1px solid #2f2f36;
        }

        body.dark-theme .table-custom tbody tr {
            background-color: #25252b;
        }

        body.dark-theme .table-custom tbody tr:hover {
            background-color: #2d2d35;
        }

        body.dark-theme .table-custom tbody td strong {
            color: #ffffff;
        }

        body.dark-theme .text-muted {
            color: #a1a1aa !important;
        }

        /* Styles pour les badges */
        body.dark-theme .badge.bg-primary {
            background-color: #4f46e5 !important;
        }

        body.dark-theme .badge.bg-success {
            background-color: #22c55e !important;
        }

        body.dark-theme .badge.bg-info {
            background-color: #06b6d4 !important;
        }

        body.dark-theme .badge.bg-warning {
            background-color: #f97316 !important;
            color: #1a1a1a;
        }

        body.dark-theme .badge.bg-danger {
            background-color: #ef4444 !important;
        }

        body.dark-theme .badge.bg-secondary {
            background-color: #4a4a55 !important;
            color: #e4e4e7;
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
                       <small data-translate="main_menu">MENU PRINCIPAL</small>
                    </h6>

                    <ul class="nav flex-column mb-5">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-page="dashboard">
                                <i class="bi bi-speedometer2"></i>
                                 <span data-translate="dashboard">Tableau de bord</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="commandes">
                                <i class="bi bi-cart"></i>
                                <span data-translate="orders">Commandes</span>
                                <span class="badge bg-danger badge-notification" id="commandes-badge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="ventes">
                                <i class="bi bi-cash-coin"></i>
                                <span data-translate="sales">Ventes</span>
                                 <span class="badge bg-warning badge-notification" id="ventes-badge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="livraisons">
                                <i class="bi bi-truck"></i>
                                <span data-translate="deliveries">Livraisons</span>
                                <span class="badge bg-warning badge-notification" id="livraisons-badge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="produits">
                                <i class="bi bi-box"></i>
                                <span data-translate="products">Produits</span>
                                 <span class="badge bg-warning badge-notification" id="produits-badge">0</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#" data-page="clients">
                                <i class="bi bi-people"></i>
                                <span data-translate="clients">Clients</span>
                                <span class="badge bg-warning badge-notification" id="clients-badge">0</span>
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="clients">
                                <i class="bi bi-people"></i>
                                <span data-translate="clients">Clients</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="versements">
                                <i class="bi bi-wallet2"></i>
                                <span data-translate="payments">Versements</span>
                                <span class="badge bg-warning badge-notification" id="versements-badge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="activites">
                                <i class="bi bi-activity"></i>
                                <span data-translate="activities">Activités</span>
                                <span class="badge bg-danger badge-notification" id="activites-badge"></span>
                            </a>
                        </li>
                    </ul>

                    <!-- Section Analytique -->
                    <h6 class="sidebar-heading text-muted mb-3">
                        <small data-translate="analytics">ANALYTIQUE</small>
                    </h6>

                    <ul class="nav flex-column mb-5">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="rapports">
                                <i class="bi bi-graph-up"></i>
                                <span data-translate="reports">Rapports</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Section Administration -->
                    <h6 class="sidebar-heading text-muted mb-3">
                        <small data-translate="administration">ADMINISTRATION</small>
                    </h6>

                    <ul class="nav flex-column mb-5">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="users">
                                <i class="bi bi-person-badge"></i>
                                <span data-translate="users">Utilisateurs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-page="settings">
                                <i class="bi bi-gear"></i>
                                <span data-translate="settings">Paramètres</span>
                            </a>
                        </li>
                    </ul>
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
    <!-- Modal Changement de Mot de Passe -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="bi bi-lock me-2"></i>
                    Changer le mot de passe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="passwordMessage" class="alert d-none" role="alert"></div>

                <form id="changePasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Ancien mot de passe</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <small class="text-muted">Minimum 8 caractères</small>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitChangePassword()">
                    <i class="bi bi-check-circle me-1"></i>
                    Changer le mot de passe
                </button>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables globales
     let currentPage = 'dashboard';


        // Données dynamiques depuis le backend
        const stats = @json($stats ?? []);
        const activities = @json($activities ?? []);
        const periode = '{{ $periode ?? "mois" }}';
        const newCount = {{ $newCount ?? 0 }};

        // Variables globales
        let livraisonsData = [];
        let terrainsData = [];
        let chauffeursData = [];
        let commandesData = [];

        // Dans votre section des variables globales
        let produitsData = [];

        // Puis au chargement des données initiales
        const initialProduits = @json($produits ?? []);
        produitsData = initialProduits;

        // NOUVELLES DONNÉES : Livraisons, terrains, chauffeurs, commandes depuis la base
        const initialLivraisons = @json($livraisons ?? []);
        const initialTerrains = @json($terrains ?? []);
        const initialChauffeurs = @json($chauffeurs ?? []);
        const initialCommandes = @json($commandes ?? []);

        // Initialiser les données globales
        livraisonsData = initialLivraisons;
        terrainsData = initialTerrains;
        chauffeursData = initialChauffeurs;
        commandesData = initialCommandes;

        // Afficher dans la console pour vérifier (à supprimer en production)
        console.log('Livraisons chargées:', livraisonsData.length);
        console.log('Terrains chargés:', terrainsData.length);
        console.log('Chauffeurs chargés:', chauffeursData.length);
        console.log('Commandes chargées:', commandesData.length);
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
    const commandesBadge = document.getElementById('commandes-badge');
    if (commandesBadge) {
        commandesBadge.textContent = enAttente;
        commandesBadge.style.display = enAttente > 0 ? 'inline' : 'none';
    }

    // Versements en attente
    const versementsEnAttente = stats.versements?.en_attente || 0;
    const versementsBadge = document.getElementById('versements-badge');
    if (versementsBadge) {
        versementsBadge.textContent = versementsEnAttente;
        versementsBadge.style.display = versementsEnAttente > 0 ? 'inline' : 'none';
    }

    // Livraisons en cours
    const livraisonsEnCours = livraisonsData.filter(l => l.statut === 'en_cours').length;
    const livraisonsBadge = document.getElementById('livraisons-badge');
    if (livraisonsBadge) {
        livraisonsBadge.textContent = livraisonsEnCours;
        livraisonsBadge.style.display = livraisonsEnCours > 0 ? 'inline' : 'none';
    }

    // Activités en attente
    const activitesBadge = document.getElementById('activites-badge');
    if (activitesBadge) {
        activitesBadge.textContent = newCount;
        activitesBadge.style.display = newCount > 0 ? 'inline' : 'none';
    }

    // PRODUITS - Appel API pour récupérer le nombre
    fetch('/produits/count', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const produitsBadge = document.getElementById('produits-badge');
            if (produitsBadge) {
                produitsBadge.textContent = data.count;
                produitsBadge.style.display = data.count > 0 ? 'inline' : 'none';
            }
        }
    })
    .catch(error => console.error('Erreur chargement compteur produits:', error));
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

    // Logout - vérifier que l'élément existe
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = '/login';
            }
        });
    }
}
function navigateTo(page) {
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-page') === page) {
            link.classList.add('active');
        }
    });

    currentPage = page;

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
        case 'produits':
            loadProduits();
            break;
        case 'activites':
            loadActivites();
            break;
        case 'users':
            loadUsers();  // ← Ajoutez cette ligne
            break;
        default:
            loadPlaceholderPage(page);
            break;
        case 'clients':
        loadClients();
        break;
        }
}
// ========== PRODUITS ==========
function loadProduits() {
    // Afficher un loader pendant le chargement
    document.getElementById('main-content').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3">Chargement des produits...</p>
        </div>
    `;

    // Faire une requête AJAX pour récupérer le contenu de la page produits
    fetch('/produits', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('main-content').innerHTML = html;

        // Réinitialiser les traductions après le chargement
        const savedLang = localStorage.getItem('language') || 'fr';
        updateUILanguage(savedLang);

        // Réinitialiser les événements spécifiques aux produits si nécessaire
        initProduitsEvents();
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('main-content').innerHTML = `
            <div class="alert alert-danger m-4">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Erreur lors du chargement de la page des produits.
                <button class="btn btn-sm btn-outline-danger ms-3" onclick="loadProduits()">Réessayer</button>
            </div>
        `;
    });
}

// ========== UTILISATEURS ==========
function loadUsers() {
    // Afficher un loader pendant le chargement
    document.getElementById('main-content').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3">Chargement des utilisateurs...</p>
        </div>
    `;

    // Faire une requête AJAX pour récupérer le contenu de la page utilisateurs
    fetch('/users', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur HTTP ' + response.status);
        }
        return response.text();
    })
    .then(html => {
        document.getElementById('main-content').innerHTML = html;

        // Réinitialiser les traductions après le chargement
        const savedLang = localStorage.getItem('language') || 'fr';
        updateUILanguage(savedLang);

        // Réinitialiser les événements spécifiques aux utilisateurs
        initUsersEvents();

        // Réinitialiser les événements Bootstrap pour les onglets
        initializeBootstrapTabs();
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('main-content').innerHTML = `
            <div class="alert alert-danger m-4">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Erreur lors du chargement de la page des utilisateurs: ${error.message}
                <button class="btn btn-sm btn-outline-danger ms-3" onclick="loadUsers()">Réessayer</button>
            </div>
        `;
    });
}

// Fonction pour initialiser les événements spécifiques aux utilisateurs
function initUsersEvents() {
    // S'assurer que les fonctions globales sont disponibles
    window.openUserModal = openUserModal;
    window.editUser = editUser;
    window.saveUser = saveUser;
    window.deleteUser = deleteUser;

    // Gestion du changement d'onglet
    const tabs = document.querySelectorAll('#userTabs button');
    tabs.forEach(tab => {
        tab.removeEventListener('click', handleTabClick);
        tab.addEventListener('click', handleTabClick);
    });

    // Restaurer l'onglet actif
    const activeTab = localStorage.getItem('activeUserTab');
    if (activeTab) {
        const tabButton = document.querySelector(`button[data-bs-target="${activeTab}"]`);
        if (tabButton) {
            const bsTab = new bootstrap.Tab(tabButton);
            bsTab.show();
        }
    }

    // Réinitialiser l'aperçu photo
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.removeEventListener('change', handlePhotoPreview);
        photoInput.addEventListener('change', handlePhotoPreview);
    }
}

function handleTabClick(e) {
    const target = this.getAttribute('data-bs-target');
    if (target) {
        localStorage.setItem('activeUserTab', target);
    }
}

function handlePhotoPreview(e) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewImg = document.getElementById('preview_img');
        const previewDiv = document.getElementById('photo_preview');
        if (previewImg && previewDiv) {
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
        }
    }
    if (this.files && this.files[0]) {
        reader.readAsDataURL(this.files[0]);
    }
}

function initializeBootstrapTabs() {
    // Initialiser les onglets Bootstrap
    const triggerTabList = [].slice.call(document.querySelectorAll('#userTabs button'));
    triggerTabList.forEach(function(triggerEl) {
        new bootstrap.Tab(triggerEl);
    });
}

// Fonctions CRUD pour les utilisateurs
function openUserModal(role) {
    console.log('Opening modal for role:', role);

    // Vérifier que le modal existe
    const modalElement = document.getElementById('userModal');
    if (!modalElement) {
        console.error('Modal element not found');
        return;
    }

    // Réinitialiser le formulaire
    const form = document.getElementById('userForm');
    if (form) form.reset();

    const userIdField = document.getElementById('user_id');
    const userRoleField = document.getElementById('user_role');
    const roleSelect = document.getElementById('role_select');
    const modalTitle = document.getElementById('userModalTitle');
    const passwordField = document.getElementById('password_field');
    const photoPreview = document.getElementById('photo_preview');

    if (userIdField) userIdField.value = '';
    if (userRoleField) userRoleField.value = role;
    if (roleSelect) roleSelect.value = role === 'admin' ? 'admin' : 'commercial';
    if (modalTitle) modalTitle.innerHTML = role === 'admin' ? 'Ajouter un administrateur' : 'Ajouter un commercial';
    if (passwordField) passwordField.style.display = 'block';
    if (photoPreview) photoPreview.style.display = 'none';

    // Ouvrir la modale
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

function editUser(id) {
    console.log('Editing user:', id);

    fetch(`/users/${id}/edit`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur HTTP ' + response.status);
        }
        return response.json();
    })
    .then(data => {
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
            alert(data.message || 'Erreur lors du chargement');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du chargement de l\'utilisateur');
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

    // Afficher les données envoyées dans la console
    console.log('=== DONNÉES ENVOYÉES ===');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    const saveBtn = document.querySelector('#userModal .btn-primary');
    const originalText = saveBtn ? saveBtn.innerHTML : 'Enregistrer';
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> En cours...';
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        console.log('=== RÉPONSE SERVEUR ===');
        console.log('Statut:', response.status);
        console.log('Réponse:', data);

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                // Afficher les erreurs champ par champ
                let errorMsg = 'Erreurs de validation:\n';
                for (const [field, messages] of Object.entries(data.errors)) {
                    errorMsg += `- ${field}: ${messages.join(', ')}\n`;
                }
                throw new Error(errorMsg);
            }
            throw new Error(data.message || 'Erreur serveur');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
            if (modal) modal.hide();
            showNotification('Succès', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert(error.message); // Pour voir l'erreur immédiatement
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



// Fonction pour initialiser les événements spécifiques aux produits
function initProduitsEvents() {
    // Gestion de la suppression des produits (si les boutons sont présents)
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id') || this.closest('tr')?.querySelector('td:first-child')?.textContent;
            if (id && confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                deleteProduct(id);
            }
        };
    });
}

// Fonction pour supprimer un produit via AJAX
function deleteProduct(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) return;

    fetch(`/produits/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message) {
            showNotification('Succès', 'Produit supprimé avec succès', 'success');
            loadProduits(); // Recharger la liste
        } else {
            showNotification('Erreur', data.message || 'Erreur lors de la suppression', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur', 'Erreur lors de la suppression', 'danger');
    });
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
    const dashboardPage = document.getElementById('main-content');
    if (!dashboardPage) return;

    const content = `
    <div class="main-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-speedometer2 text-primary me-2"></i>
                    <span data-translate="dashboard_title">Tableau de bord Administrateur</span>
                </h1>
                <p class="text-muted mb-0" data-translate="dashboard_subtitle">Vue d\'ensemble de votre activité commerciale</p>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">A</div>
                    <span id="user-name" class="fw-bold">Admin</span>
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li class="px-3 py-2 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px;">A</div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Admin</h6>
                                <small class="text-muted">admin@gestcomm.com</small>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i> Mon Profil</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openChangePasswordModal()"><i class="bi bi-lock me-2"></i> Changer Mot de Passe</a></li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <li>
                        <form method="POST" action="/logout" id="logout-form" style="display: none;">
                            <input type="hidden" name="_token" value="' + document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') + '">
                        </form>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</a>
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
                            <div class="stat-icon primary"><i class="bi bi-currency-exchange"></i></div>
                            <p class="text-muted mb-1">CHIFFRE D\'AFFAIRES</p>
                            <h2 class="stat-number">${(stats.chiffre_affaires || 0).toLocaleString('fr-FR')}<small class="fs-6 text-muted">FCFA</small></h2>
                        </div>
                        <div class="text-end"><small class="text-muted"><i class="bi bi-calendar-event"></i> ${getPeriodeLabel(periode)}</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-success h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon success"><i class="bi bi-cart-check"></i></div>
                            <p class="text-muted mb-1">COMMANDES</p>
                            <div class="d-flex align-items-center">
                                <h2 class="stat-number me-3">${stats.commandes?.total || 0}</h2>
                                <div><span class="badge bg-success">${stats.commandes?.livrees || 0} livrées</span>${stats.commandes?.en_attente > 0 ? `<span class="badge bg-warning d-block mt-1">${stats.commandes?.en_attente || 0} en attente</span>` : ''}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-info h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon info"><i class="bi bi-graph-up"></i></div>
                            <p class="text-muted mb-1">VENTES</p>
                            <h2 class="stat-number">${stats.total_ventes || 0}</h2>
                            <div class="d-flex justify-content-between"><small class="text-muted">Transactions</small><small class="text-muted">${stats.total_quantite || 0} unités</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-warning h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon warning"><i class="bi bi-cash-stack"></i></div>
                            <p class="text-muted mb-1">VERSEMENTS</p>
                            <h2 class="stat-number">${((stats.versements?.valides || 0) + (stats.versements?.en_attente || 0)).toLocaleString('fr-FR')}</h2>
                            <div class="d-flex justify-content-between"><span class="badge bg-success">${(stats.versements?.valides || 0).toLocaleString('fr-FR')} validés</span>${stats.versements?.en_attente > 0 ? `<span class="badge bg-warning">${(stats.versements?.en_attente || 0).toLocaleString('fr-FR')} en attente</span>` : ''}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="performance-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Performance des commerciaux</h5>
                        <a href="#" class="btn btn-sm btn-light" onclick="navigateTo(\'rapports\')">Voir plus <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    ${stats.performance_commerciaux && stats.performance_commerciaux.length > 0 ? `
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead><tr><th>Commercial</th><th>Ventes</th><th>Quantité</th><th>Commandes</th><th>Performance</th></tr></thead>
                            <tbody>
                                ${stats.performance_commerciaux.map(commercial => {
                                    const totalVentes = commercial.total_ventes || 0;
                                    const totalCommandes = commercial.total_commandes || 0;
                                    const objectif = commercial.objectif || (commercial.total_commandes * 1.2 || 100000);
                                    const performance = Math.min(100, (totalVentes / Math.max(1, objectif)) * 100);
                                    return `<tr>
                                        <td><div class="d-flex align-items-center"><div class="commercial-avatar me-3">${commercial.nom ? commercial.nom.charAt(0).toUpperCase() : 'C'}</div><div><strong>${commercial.nom || 'Non défini'}</strong><div class="text-muted small">${commercial.role || 'Commercial'}</div></div></div></td>
                                        <td><strong class="text-primary">${totalVentes.toLocaleString('fr-FR')}</strong><small class="text-muted d-block">FCFA</small></td>
                                        <td><span class="performance-badge">${commercial.total_quantite_vendue || 0}</span></td>
                                        <td><strong class="text-success">${totalCommandes.toLocaleString('fr-FR')}</strong><small class="text-muted d-block">FCFA</small></td>
                                        <td><div class="d-flex align-items-center"><div class="progress-custom flex-grow-1 me-2"><div class="progress-bar" style="width: ${performance}%"></div></div><span class="fw-bold">${performance.toFixed(1)}%</span></div></td>
                                    </tr>`;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                    ` : `
                    <div class="text-center py-5"><i class="bi bi-bar-chart text-muted fs-1"></i><p class="text-muted mt-3">Aucune donnée de performance disponible</p><p class="text-muted small">Les données apparaîtront lorsque les commerciaux auront effectué des ventes</p></div>
                    `}
                </div>
            </div>
        </div>
    </div>
    `;

    dashboardPage.innerHTML = content;
}

// ========== CLIENTS ==========
function loadClients() {
    document.getElementById('main-content').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3">Chargement des clients...</p>
        </div>
    `;

    fetch('/clients', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('main-content').innerHTML = html;
        const savedLang = localStorage.getItem('language') || 'fr';
        updateUILanguage(savedLang);
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('main-content').innerHTML = `
            <div class="alert alert-danger m-4">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Erreur lors du chargement de la page des clients.
                <button class="btn btn-sm btn-outline-danger ms-3" onclick="loadClients()">Réessayer</button>
            </div>
        `;
    });
}
// ========== ACTIVITÉS ==========
function loadActivites() {
    document.getElementById('main-content').innerHTML = `
        <div class="main-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">
                        <i class="bi bi-activity text-primary me-2"></i>
                        <span data-translate="activities_title">Activités des commerciaux</span>
                    </h1>
                    <p class="text-muted mb-0" data-translate="activities_subtitle">Historique des activités des commerciaux</p>
                </div>
            </div>
        </div>

        <div class="activity-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>
                    <span data-translate="recent_activities">Liste des activités</span>
                    ${newCount > 0 ? `<span class="badge bg-danger ms-2">${newCount} nouvelles</span>` : ''}
                </h5>
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
                            ${activity.status === 'pending' ? `<span class="badge bg-danger" data-translate="new">NOUVEAU</span>` : ''}
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
                                <i class="bi bi-check-circle me-1"></i> <span data-translate="validate">Valider</span>
                            </button>
                            <button class="btn btn-danger btn-sm px-3" onclick="rejectActivity(${activity.id})">
                                <i class="bi bi-x-circle me-1"></i> <span data-translate="reject">Refuser</span>
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
                    <p class="text-muted mt-3" data-translate="no_activities">Aucune activité récente</p>
                    <p class="text-muted small" data-translate="activities_will_appear">Les activités des commerciaux apparaîtront ici</p>
                </div>
                `}
            </div>
        </div>
    `;

    const savedLang = localStorage.getItem('language') || 'fr';
    updateUILanguage(savedLang);
}

   // Fonction pour ouvrir la modale de changement de mot de passe
function openChangePasswordModal() {
    // Réinitialiser le formulaire
    document.getElementById('changePasswordForm').reset();
    document.getElementById('passwordMessage').classList.add('d-none');

    // Ouvrir la modale
    const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
    modal.show();
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

// Fonction pour modifier le rendu du tableau des livraisons
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
                    <strong>${livraison.commande_numero || 'N/A'}</strong>
                    <div class="text-muted small">${livraison.client_nom || 'Client inconnu'}</div>
                </td>
                <td>${livraison.terrain_nom || 'N/A'}</td>
                <td>${livraison.chauffeur_nom || 'N/A'}</td>
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
        showNotification('Erreur', 'Livraison non trouvée', 'danger');
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

    // Attendre que les options soient chargées puis sélectionner les valeurs
    setTimeout(() => {
        document.getElementById('terrain_id').value = livraison.terrain_id || '';
        document.getElementById('chauffeur_id').value = livraison.chauffeur_id || '';
        document.getElementById('commande_id').value = livraison.commande_id || '';
        document.getElementById('statut').value = livraison.statut;

        if (livraison.date_livraison) {
            const date = new Date(livraison.date_livraison);
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
            document.getElementById('date_livraison').value = date.toISOString().slice(0, 16);
        }

        if (livraison.notes) {
            document.getElementById('notes').value = livraison.notes;
        }
    }, 100);

    // Ouvrir la modale
    const modal = new bootstrap.Modal(document.getElementById('livraisonModal'));
    modal.show();
}

// Fonction pour sauvegarder une livraison (avec appel API)
function saveLivraison() {
    const form = document.getElementById('livraisonForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const id = document.getElementById('livraison_id').value;
    const data = {
        commande_id: document.getElementById('commande_id').value,
        terrain_id: document.getElementById('terrain_id').value,
        chauffeur_id: document.getElementById('chauffeur_id').value,
        date_livraison: document.getElementById('date_livraison').value,
        statut: document.getElementById('statut').value,
        notes: document.getElementById('notes').value
    };

    const url = id ? `/admin/livraisons/${id}` : '/admin/livraisons';
    const method = id ? 'PUT' : 'POST';

    // Désactiver le bouton
    const saveBtn = document.querySelector('#livraisonModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> En cours...';

    console.log('Envoi de la requête à:', url);
    console.log('Données:', data);

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Réponse reçue:', response.status);
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(result => {
        console.log('Résultat:', result);
        if (result.success) {
            // Fermer la modale
            const modal = bootstrap.Modal.getInstance(document.getElementById('livraisonModal'));
            modal.hide();

            // Recharger les données depuis le serveur
            refreshLivraisonsData();

            // Afficher une notification
            showNotification('Succès', id ? 'Livraison modifiée avec succès' : 'Livraison créée avec succès', 'success');
        } else {
            showNotification('Erreur', result.message || 'Une erreur est survenue', 'danger');
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        showNotification('Erreur', error.message || 'Erreur lors de la sauvegarde. Vérifiez la console.', 'danger');
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

   // Fonction pour rafraîchir les données des livraisons
function refreshLivraisonsData() {
    fetch('/admin/livraisons?ajax=1', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            livraisonsData = data.livraisons;
            if (currentPage === 'livraisons') {
                loadLivraisons();
            }
        }
    })
    .catch(error => console.error('Erreur rafraîchissement:', error));
}
        function openDeleteLivraisonModal(id) {
            window.currentLivraisonId = id;
            const modal = new bootstrap.Modal(document.getElementById('deleteLivraisonModal'));
            modal.show();
        }

// Fonction pour supprimer une livraison
    function confirmDeleteLivraison() {
    const id = window.currentLivraisonId;

    fetch(`/admin/livraisons/${id}`, {
    method: 'DELETE',
    headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'Accept': 'application/json'
    }
    })
    .then(response => response.json())
    .then(result => {
    if (result.success) {
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteLivraisonModal'));
    modal.hide();
    location.reload();
    } else {
    alert('Erreur: ' + (result.message || 'Une erreur est survenue'));
    }
    })
    .catch(error => {
    console.error('Erreur:', error);
    alert('Erreur lors de la suppression');
    });
    }

function loadTerrainOptions() {
const select = document.getElementById('terrain_id');
if (!select) return;

select.innerHTML = '<option value="">Sélectionner un terrain</option>';

terrainsData.forEach(terrain => {
const option = document.createElement('option');
option.value = terrain.id;
option.textContent = terrain.nom;
select.appendChild(option);
});
}

// Fonction pour charger les options de chauffeur
    function loadChauffeurOptions() {
    const select = document.getElementById('chauffeur_id');
    if (!select) return;

    select.innerHTML = '<option value="">Sélectionner un chauffeur</option>';

    chauffeursData.forEach(chauffeur => {
    const option = document.createElement('option');
    option.value = chauffeur.id;
    option.textContent = chauffeur.nom;
    select.appendChild(option);
    });
    }



// Fonction pour charger les options de commande
function loadCommandeOptions() {
const select = document.getElementById('commande_id');
if (!select) return;

select.innerHTML = '<option value="">Sélectionner une commande</option>';

commandesData.forEach(commande => {
const option = document.createElement('option');
option.value = commande.id;
option.textContent = `${commande.numero} - ${commande.client}`;
select.appendChild(option);
});
}

        // ========== SETTINGS ==========
function loadSettings() {
    const currentLang = localStorage.getItem('language') || 'fr';

    document.getElementById('main-content').innerHTML = `
        <div class="main-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">
                        <i class="bi bi-gear text-primary me-2"></i>
                        <span data-translate="settings_title">Paramètres</span>
                    </h1>
                    <p class="text-muted mb-0" data-translate="settings_subtitle">Configurez votre système de gestion commerciale</p>
                </div>
            </div>
        </div>

        <div class="settings-container">
            <div class="settings-section">
                <h2 data-translate="general">Général</h2>
                <ul class="settings-list">
                    <li><a href="#" data-translate="scheduling"><i class="bi bi-calendar-week"></i> Programmation</a></li>
                    <li><a href="#" data-translate="updates"><i class="bi bi-cloud-arrow-down"></i> Mises à jour</a></li>
                    <li><a href="#" data-translate="privacy"><i class="bi bi-shield-lock"></i> Confidentialité</a></li>
                    <li><a href="#" data-translate="subscription"><i class="bi bi-credit-card"></i> Mon abonnement</a></li>
                    <li><a href="#" data-translate="about"><i class="bi bi-info-circle"></i> À propos</a></li>
                </ul>
            </div>

            <div class="settings-section">
                <h2 data-translate="language">Langue</h2>
                <div class="language-selector">
                    <div class="language-option ${currentLang === 'fr' ? 'active' : ''}" data-lang="fr">
                        <img src="https://flagcdn.com/w40/fr.png" class="language-flag" alt="Français">
                        <span>Français</span>
                    </div>
                    <div class="language-option ${currentLang === 'en' ? 'active' : ''}" data-lang="en">
                        <img src="https://flagcdn.com/w40/us.png" class="language-flag" alt="English">
                        <span>English</span>
                    </div>
                    <div class="language-option ${currentLang === 'es' ? 'active' : ''}" data-lang="es">
                        <img src="https://flagcdn.com/w40/es.png" class="language-flag" alt="Español">
                        <span>Español</span>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="settings-section">
                <h2 data-translate="theme">Thème</h2>
                <p class="text-muted mb-3" data-translate="theme_desc">Choisissez l'apparence de votre GestComm :</p>
                <div class="theme-options">
                    <div class="theme-card active" data-theme="clair">
                        <div class="theme-icon text-warning"><i class="bi bi-sun"></i></div>
                        <div class="theme-name" data-translate="light">Clair</div>
                        <div class="theme-desc" data-translate="light_desc">Interface lumineuse</div>
                    </div>
                    <div class="theme-card" data-theme="sombre">
                        <div class="theme-icon text-secondary"><i class="bi bi-moon"></i></div>
                        <div class="theme-name" data-translate="dark">Sombre</div>
                        <div class="theme-desc" data-translate="dark_desc">Interface foncée</div>
                    </div>
                    <div class="theme-card" data-theme="auto">
                        <div class="theme-icon text-primary"><i class="bi bi-circle-half"></i></div>
                        <div class="theme-name" data-translate="auto">Automatique</div>
                        <div class="theme-desc" data-translate="auto_desc">Suit votre système</div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="settings-section">
                <h2 data-translate="advanced_settings">Paramètres avancés</h2>
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="ignore-auth" checked>
                        <span class="slider"></span>
                    </label>
                    <span data-translate="ignore_auth">Ignorer la demande d'autorisation lors du démarrage</span>
                </div>
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="system-icon" checked>
                        <span class="slider"></span>
                    </label>
                    <span data-translate="show_system_icon">Afficher l'icône dans la barre d'état système</span>
                </div>
            </div>

            <div class="settings-section">
                <h2 data-translate="commercial_config">Configuration commerciale</h2>
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="email-notif" checked>
                        <span class="slider"></span>
                    </label>
                    <span data-translate="email_notifications">Notifications par email pour nouvelles commandes</span>
                </div>
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="approval-required">
                        <span class="slider"></span>
                    </label>
                    <span data-translate="approval_required">Approbation requise pour commandes > 500.000 FCFA</span>
                </div>
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="stock-alerts" checked>
                        <span class="slider"></span>
                    </label>
                    <span data-translate="stock_alerts">Alertes stock bas automatiques</span>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <button class="btn btn-outline-secondary" onclick="resetSettings()">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                    <span data-translate="reset_settings">Restaurer les paramètres par défaut</span>
                </button>
                <div>
                    <button class="btn btn-outline-danger me-2" onclick="cancelSettings()">
                        <i class="bi bi-x-circle me-2"></i>
                        <span data-translate="cancel">Annuler</span>
                    </button>
                    <button class="btn btn-primary" onclick="saveSettings()">
                        <i class="bi bi-check-circle me-2"></i>
                        <span data-translate="save_settings">Enregistrer les modifications</span>
                    </button>
                </div>
            </div>
        </div>
    `;

    setupSettingsEvents();
}

// Fonction helper pour les traductions en JavaScript
function getTranslation(key, lang) {
    const translations = {
        fr: {
            settings_title: 'Paramètres',
            settings_subtitle: 'Configurez votre système de gestion commerciale',
            general: 'Général',
            scheduling: 'Programmation',
            updates: 'Mises à jour',
            privacy: 'Confidentialité',
            subscription: 'Mon abonnement',
            about: 'À propos',
            language: 'Langue',
            theme: 'Thème',
            light: 'Clair',
            dark: 'Sombre',
            auto: 'Automatique',
            light_desc: 'Interface lumineuse',
            dark_desc: 'Interface foncée',
            auto_desc: 'Suit votre système',
            // ... autres traductions
        },
        en: {
            settings_title: 'Settings',
            settings_subtitle: 'Configure your business management system',
            general: 'General',
            scheduling: 'Scheduling',
            updates: 'Updates',
            privacy: 'Privacy',
            subscription: 'My Subscription',
            about: 'About',
            language: 'Language',
            theme: 'Theme',
            light: 'Light',
            dark: 'Dark',
            auto: 'Auto',
            light_desc: 'Light interface',
            dark_desc: 'Dark interface',
            auto_desc: 'Follow your system',
            // ... other translations
        },
        es: {
            settings_title: 'Configuración',
            settings_subtitle: 'Configure su sistema de gestión comercial',
            general: 'General',
            scheduling: 'Programación',
            updates: 'Actualizaciones',
            privacy: 'Privacidad',
            subscription: 'Mi suscripción',
            about: 'Acerca de',
            language: 'Idioma',
            theme: 'Tema',
            light: 'Claro',
            dark: 'Oscuro',
            auto: 'Automático',
            light_desc: 'Interfaz clara',
            dark_desc: 'Interfaz oscura',
            auto_desc: 'Sigue tu sistema',
            // ... otras traducciones
        }
    };

    return translations[lang]?.[key] || translations['fr'][key] || key;
}

// Fonction pour soumettre le changement de mot de passe
function submitChangePassword() {
    const oldPassword = document.getElementById('old_password').value;
    const newPassword = document.getElementById('new_password').value;
    const newPasswordConfirm = document.getElementById('new_password_confirmation').value;

    if (!oldPassword || !newPassword || !newPasswordConfirm) {
        showPasswordMessage('Veuillez remplir tous les champs', 'danger');
        return;
    }

    if (newPassword.length < 8) {
        showPasswordMessage('Le nouveau mot de passe doit contenir au moins 8 caractères', 'danger');
        return;
    }

    if (newPassword !== newPasswordConfirm) {
        showPasswordMessage('Les nouveaux mots de passe ne correspondent pas', 'danger');
        return;
    }

    // Utiliser la nouvelle route
    fetch('/profile/change-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            old_password: oldPassword,
            new_password: newPassword,
            new_password_confirmation: newPasswordConfirm
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showPasswordMessage(result.message, 'success');
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                modal.hide();
                // Rediriger vers la page de connexion après changement
                window.location.href = '/login';
            }, 2000);
        } else {
            showPasswordMessage(result.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showPasswordMessage('Une erreur est survenue. Veuillez réessayer.', 'danger');
    });es
}
    // Fonction pour afficher les messages dans la modale
    function showPasswordMessage(message, type) {
    const messageDiv = document.getElementById('passwordMessage');
    messageDiv.textContent = message;
    messageDiv.className = `alert alert-${type}`;
    messageDiv.classList.remove('d-none');

    // Faire défiler vers le message
    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Fonction pour afficher une notification (à ajouter)
function showNotification(title, message, type = 'success') {
    // Créer le conteneur de toast s'il n'existe pas
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    const toastId = 'toast-' + Date.now();
    const bgColor = type === 'success' ? 'bg-success' : (type === 'danger' ? 'bg-danger' : 'bg-info');

    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>
                    <small>${message}</small>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();

    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function setupSettingsEvents() {
    // Gestion des thèmes
    const themeCards = document.querySelectorAll('.theme-card');
    if (themeCards.length > 0) {
        themeCards.forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                // Appliquer le thème immédiatement
                const theme = this.getAttribute('data-theme');
                applyTheme(theme);
            });
        });
    }

    // Gestion des langues
    const languageOptions = document.querySelectorAll('.language-option');
    if (languageOptions.length > 0) {
        languageOptions.forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.language-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');

                // Changer la langue (à implémenter selon vos besoins)
                const lang = this.getAttribute('data-lang');
                changeLanguage(lang);
            });
        });
    }

    // Charger les paramètres sauvegardés
    loadSavedSettings();
}


function applyTheme(theme) {
    if (theme === 'sombre') {
        document.body.classList.add('dark-theme');
        localStorage.setItem('theme', 'sombre');

        // Supprimer l'ancien style si existant pour éviter les doublons
        const oldStyle = document.getElementById('dark-theme-style');
        if (oldStyle) oldStyle.remove();

        // Le style est déjà dans votre CSS, pas besoin d'en ajouter un nouveau
    } else if (theme === 'clair') {
        document.body.classList.remove('dark-theme');
        localStorage.setItem('theme', 'clair');
    } else if (theme === 'auto') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
        }
        localStorage.setItem('theme', 'auto');

        // Écouter les changements système
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (localStorage.getItem('theme') === 'auto') {
                if (e.matches) {
                    document.body.classList.add('dark-theme');
                } else {
                    document.body.classList.remove('dark-theme');
                }
            }
        });
    }
}

function changeLanguage(lang) {
    console.log('Changement de langue vers:', lang);

    // Désactiver temporairement les options de langue
    const langOptions = document.querySelectorAll('.language-option');
    langOptions.forEach(opt => {
        opt.style.pointerEvents = 'none';
        opt.style.opacity = '0.5';
    });

    fetch('/change-language', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ lang: lang })
    })
    .then(response => {
        console.log('Réponse langue:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Résultat langue:', data);
        if (data.success) {
            // Mettre à jour l'interface
            updateUILanguage(lang);

            // Sauvegarder dans localStorage
            localStorage.setItem('language', lang);

            // Mettre à jour l'attribut lang du document
            document.documentElement.lang = lang;

            // Mettre à jour l'option active
            document.querySelectorAll('.language-option').forEach(opt => {
                if (opt.getAttribute('data-lang') === lang) {
                    opt.classList.add('active');
                } else {
                    opt.classList.remove('active');
                }
            });

            showNotification('Succès', `Langue changée en ${getLanguageName(lang)}`, 'success');
        } else {
            showNotification('Erreur', data.message || 'Erreur lors du changement', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur détaillée langue:', error);
        showNotification('Erreur', 'Erreur de connexion au serveur', 'danger');
    })
    .finally(() => {
        // Réactiver les options
        langOptions.forEach(opt => {
            opt.style.pointerEvents = '';
            opt.style.opacity = '';
        });
    });
}

// Fonction pour obtenir le nom de la langue
function getLanguageName(lang) {
    const names = {
        fr: 'Français',
        en: 'Anglais',
        es: 'Espagnol'
    };
    return names[lang] || lang;
}

// Fonction pour mettre à jour l'interface utilisateur avec la nouvelle langue
function updateUILanguage(lang) {
    const translations = {
        fr: {
            // Menu principal
            'main_menu': 'MENU PRINCIPAL',
            'dashboard': 'Tableau de bord',
            'orders': 'Commandes',
            'sales': 'Ventes',
            'deliveries': 'Livraisons',
            'products': 'Produits',
            'clients': 'Clients',
            'payments': 'Versements',
            'analytics': 'ANALYTIQUE',
            'reports': 'Rapports',
            'administration': 'ADMINISTRATION',
            'users': 'Utilisateurs',
            'settings': 'Paramètres',
            'activities': 'Activités',
            'activities_title': 'Activités des commerciaux',
            'activities_subtitle': 'Historique des activités des commerciaux',
            'users_management': 'Gestion des Utilisateurs',
            'users_subtitle': 'Gérez les administrateurs et les commerciaux',
            'admins': 'Administrateurs',
            'commerciaux': 'Commerciaux',
            'admins_list': 'Liste des administrateurs',
            'commerciaux_list': 'Liste des commerciaux',
            'add_admin': 'Ajouter un administrateur',
            'add_commercial': 'Ajouter un commercial',
            'photo': 'Photo',
            'name': 'Nom',
            'email': 'Email',
            'role': 'Rôle',
            'status': 'Statut',
            'created_at': 'Date création',
            'active': 'Actif',
            'inactive': 'Inactif',

            // Dashboard
            'dashboard_title': 'Tableau de bord Administrateur',
            'dashboard_subtitle': 'Vue d\'ensemble de votre activité commerciale',
            'revenue': 'CHIFFRE D\'AFFAIRES',
            'transactions': 'Transactions',
            'units': 'unités',
            'delivered': 'livrées',
            'pending': 'en attente',
            'validated': 'validés',
            'waiting': 'en attente',

            // Performance
            'commercial_performance': 'Performance des commerciaux',
            'view_more': 'Voir plus',
            'commercial': 'Commercial',
            'amount': 'Ventes',
            'quantity': 'Quantité',
            'performance': 'Performance',
            'no_performance_data': 'Aucune donnée de performance disponible',
            'performance_will_appear': 'Les données apparaîtront lorsque les commerciaux auront effectué des ventes',

            // Activités
            'recent_activities': 'Activités récentes des commerciaux',
            'new': 'NOUVEAU',
            'validate': 'Valider',
            'reject': 'Refuser',
            'validated_status': 'Validé',
            'processed': 'Traité',
            'no_activities': 'Aucune activité récente',
            'activities_will_appear': 'Les activités des commerciaux apparaîtront ici',

            // Profil
            'my_profile': 'Mon Profil',
            'change_password': 'Changer Mot de Passe',
            'logout': 'Déconnexion',
            'connected': 'Connecté',
            'member_since': 'Membre depuis',
            'personal_info': 'Informations personnelles',
            'full_name': 'Nom complet',
            'email': 'Email',
            'phone': 'Téléphone',
            'update': 'Mettre à jour',
            'change_photo': 'Changer la photo',

            // Paramètres
            'settings_title': 'Paramètres',
            'settings_subtitle': 'Configurez votre système de gestion commerciale',
            'general': 'Général',
            'scheduling': 'Programmation',
            'updates': 'Mises à jour',
            'privacy': 'Confidentialité',
            'subscription': 'Mon abonnement',
            'about': 'À propos',
            'language': 'Langue',
            'theme': 'Thème',
            'light': 'Clair',
            'dark': 'Sombre',
            'auto': 'Automatique',
            'light_desc': 'Interface lumineuse',
            'dark_desc': 'Interface foncée',
            'auto_desc': 'Suit votre système',
            'theme_desc': 'Choisissez l\'apparence de votre GestComm :',
            'advanced_settings': 'Paramètres avancés',
            'ignore_auth': 'Ignorer la demande d\'autorisation lors du démarrage',
            'show_system_icon': 'Afficher l\'icône dans la barre d\'état système',
            'commercial_config': 'Configuration commerciale',
            'email_notifications': 'Notifications par email pour nouvelles commandes',
            'approval_required': 'Approbation requise pour commandes > 500.000 FCFA',
            'stock_alerts': 'Alertes stock bas automatiques',
            'reset_settings': 'Restaurer les paramètres par défaut',
            'cancel': 'Annuler',
            'save_settings': 'Enregistrer les modifications'
        },
        en: {
            'main_menu': 'MAIN MENU',
            'dashboard': 'Dashboard',
            'orders': 'Orders',
            'sales': 'Sales',
            'deliveries': 'Deliveries',
            'products': 'Products',
            'clients': 'Clients',
            'payments': 'Payments',
            'analytics': 'ANALYTICS',
            'reports': 'Reports',
            'administration': 'ADMINISTRATION',
            'users': 'Users',
            'settings': 'Settings',
            'dashboard_title': 'Admin Dashboard',
            'dashboard_subtitle': 'Overview of your business activity',
            'revenue': 'REVENUE',
            'transactions': 'Transactions',
            'units': 'units',
            'delivered': 'delivered',
            'pending': 'pending',
            'validated': 'validated',
            'waiting': 'waiting',
            'commercial_performance': 'Commercial Performance',
            'view_more': 'View more',
            'commercial': 'Commercial',
            'amount': 'Sales',
            'quantity': 'Quantity',
            'performance': 'Performance',
            'no_performance_data': 'No performance data available',
            'performance_will_appear': 'Data will appear when sales are made',
            'recent_activities': 'Recent Commercial Activities',
            'new': 'NEW',
            'validate': 'Validate',
            'reject': 'Reject',
            'validated_status': 'Validated',
            'processed': 'Processed',
            'no_activities': 'No recent activities',
            'activities_will_appear': 'Commercial activities will appear here',
            'my_profile': 'My Profile',
            'change_password': 'Change Password',
            'logout': 'Logout',
            'connected': 'Connected',
            'member_since': 'Member since',
            'personal_info': 'Personal Information',
            'full_name': 'Full Name',
            'email': 'Email',
            'phone': 'Phone',
            'update': 'Update',
            'change_photo': 'Change Photo',
            'settings_title': 'Settings',
            'settings_subtitle': 'Configure your business management system',
            'general': 'General',
            'scheduling': 'Scheduling',
            'updates': 'Updates',
            'privacy': 'Privacy',
            'subscription': 'My Subscription',
            'about': 'About',
            'language': 'Language',
            'theme': 'Theme',
            'light': 'Light',
            'dark': 'Dark',
            'auto': 'Auto',
            'light_desc': 'Light interface',
            'dark_desc': 'Dark interface',
            'auto_desc': 'Follow your system',
            'theme_desc': 'Choose your GestComm appearance:',
            'advanced_settings': 'Advanced Settings',
            'ignore_auth': 'Ignore authorization request on startup',
            'show_system_icon': 'Show icon in system tray',
            'commercial_config': 'Commercial Configuration',
            'email_notifications': 'Email notifications for new orders',
            'approval_required': 'Approval required for orders > 500,000 FCFA',
            'stock_alerts': 'Automatic low stock alerts',
            'reset_settings': 'Reset to default settings',
            'cancel': 'Cancel',
            'save_settings': 'Save changes',
            'activities': 'Activities',
            'activities_title': 'Commercial Activities',
            'activities_subtitle': 'History of commercial activities',
        },
        es: {
            'main_menu': 'MENÚ PRINCIPAL',
            'dashboard': 'Tablero',
            'orders': 'Pedidos',
            'sales': 'Ventas',
            'deliveries': 'Entregas',
            'products': 'Productos',
            'clients': 'Clientes',
            'payments': 'Pagos',
            'analytics': 'ANALÍTICA',
            'reports': 'Informes',
            'administration': 'ADMINISTRACIÓN',
            'users': 'Usuarios',
            'settings': 'Ajustes',
            'dashboard_title': 'Tablero de Administrador',
            'dashboard_subtitle': 'Visión general de su actividad comercial',
            'revenue': 'INGRESOS',
            'transactions': 'Transacciones',
            'units': 'unidades',
            'delivered': 'entregados',
            'pending': 'pendientes',
            'validated': 'validados',
            'waiting': 'en espera',
            'commercial_performance': 'Rendimiento Comercial',
            'view_more': 'Ver más',
            'commercial': 'Comercial',
            'amount': 'Ventas',
            'quantity': 'Cantidad',
            'performance': 'Rendimiento',
            'no_performance_data': 'No hay datos de rendimiento disponibles',
            'performance_will_appear': 'Los datos aparecerán cuando se realicen ventas',
            'recent_activities': 'Actividades Recientes',
            'new': 'NUEVO',
            'validate': 'Validar',
            'reject': 'Rechazar',
            'validated_status': 'Validado',
            'processed': 'Procesado',
            'no_activities': 'No hay actividades recientes',
            'activities_will_appear': 'Las actividades comerciales aparecerán aquí',
            'my_profile': 'Mi Perfil',
            'change_password': 'Cambiar Contraseña',
            'logout': 'Cerrar Sesión',
            'connected': 'Conectado',
            'member_since': 'Miembro desde',
            'personal_info': 'Información Personal',
            'full_name': 'Nombre Completo',
            'email': 'Correo',
            'phone': 'Teléfono',
            'update': 'Actualizar',
            'change_photo': 'Cambiar Foto',
            'settings_title': 'Configuración',
            'settings_subtitle': 'Configure su sistema de gestión comercial',
            'general': 'General',
            'scheduling': 'Programación',
            'updates': 'Actualizaciones',
            'privacy': 'Privacidad',
            'subscription': 'Mi suscripción',
            'about': 'Acerca de',
            'language': 'Idioma',
            'theme': 'Tema',
            'light': 'Claro',
            'dark': 'Oscuro',
            'auto': 'Automático',
            'light_desc': 'Interfaz clara',
            'dark_desc': 'Interfaz oscura',
            'auto_desc': 'Sigue tu sistema',
            'theme_desc': 'Elija la apariencia de su GestComm:',
            'advanced_settings': 'Configuración avanzada',
            'ignore_auth': 'Ignorar solicitud de autorización al iniciar',
            'show_system_icon': 'Mostrar icono en la bandeja del sistema',
            'commercial_config': 'Configuración comercial',
            'email_notifications': 'Notificaciones por email para nuevos pedidos',
            'approval_required': 'Aprobación requerida para pedidos > 500.000 FCFA',
            'stock_alerts': 'Alertas automáticas de stock bajo',
            'reset_settings': 'Restaurar configuración por defecto',
            'cancel': 'Cancelar',
            'save_settings': 'Guardar cambios',
            'activities': 'Actividades',
            'activities_title': 'Actividades de los comerciales',
            'activities_subtitle': 'Historial de actividades comerciales',
        }
    };

    // Mettre à jour tous les éléments avec data-translate
    const elements = document.querySelectorAll('[data-translate]');
    elements.forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[lang] && translations[lang][key]) {
            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                element.placeholder = translations[lang][key];
            } else {
                element.textContent = translations[lang][key];
            }
        }
    });

    // Mettre à jour les éléments sans data-translate (sidebar)
    updateSidebarLanguage(lang, translations);
    updateDashboardLanguage(lang, translations);

    // Mettre à jour l'attribut lang du document
    document.documentElement.lang = lang;

    // Sauvegarder dans localStorage
    localStorage.setItem('language', lang);
}


// Fonction pour mettre à jour la sidebar
function updateSidebarLanguage(lang, translations) {
    const sidebarTexts = {
        'main_menu': '.sidebar-heading small',
        'analytics': '.sidebar-heading:contains("ANALYTIQUE") small',
        'administration': '.sidebar-heading:contains("ADMINISTRATION") small'
    };

    // Mettre à jour les titres de section
    document.querySelectorAll('.sidebar-heading small').forEach((el, index) => {
        if (index === 0 && translations[lang]['main_menu']) {
            el.textContent = translations[lang]['main_menu'];
        } else if (index === 1 && translations[lang]['analytics']) {
            el.textContent = translations[lang]['analytics'];
        } else if (index === 2 && translations[lang]['administration']) {
            el.textContent = translations[lang]['administration'];
        }
    });

    // Mettre à jour les liens de navigation
    const navItems = document.querySelectorAll('.nav-link');
    const navKeys = ['dashboard', 'orders', 'sales', 'deliveries', 'products', 'clients', 'payments', 'reports', 'users', 'settings'];

    navItems.forEach((item, index) => {
        if (index < navKeys.length && translations[lang][navKeys[index]]) {
            // Garder l'icône et le badge
            const icon = item.querySelector('i');
            const badge = item.querySelector('.badge');
            const text = translations[lang][navKeys[index]];

            if (icon) {
                item.innerHTML = '';
                item.appendChild(icon.cloneNode(true));
                item.appendChild(document.createTextNode(' ' + text));
                if (badge) {
                    item.appendChild(badge.cloneNode(true));
                }
            }
        }
    });
}

// Fonction pour mettre à jour le dashboard (si affiché)
function updateDashboardLanguage(lang, translations) {
    // Si le dashboard est affiché, mettre à jour ses éléments
    if (document.querySelector('.main-header h1')) {
        const headerH1 = document.querySelector('.main-header h1');
        const headerP = document.querySelector('.main-header p');

        if (headerH1 && translations[lang]['dashboard_title']) {
            const icon = headerH1.querySelector('i');
            headerH1.innerHTML = '';
            if (icon) headerH1.appendChild(icon.cloneNode(true));
            headerH1.appendChild(document.createTextNode(' ' + translations[lang]['dashboard_title']));
        }

        if (headerP && translations[lang]['dashboard_subtitle']) {
            headerP.textContent = translations[lang]['dashboard_subtitle'];
        }
    }
}

// Initialiser les traductions au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadDashboard();
    setupEventListeners();
    updateBadges();
    // Ajouter l'attribut data-translate à tous les éléments textuels
    addTranslationAttributes();

    // Charger la langue sauvegardée
    const savedLang = localStorage.getItem('language') || 'fr';
    updateUILanguage(savedLang);

    // Initialiser le badge des produits
    updateProduitsBadge();

    // Auto-refresh toutes les 5 minutes
    setInterval(() => {
        if (currentPage === 'dashboard') {
            loadDashboard();
        }
        updateBadges(); // Rafraîchir aussi les badges
    }, 300000);
});

// Fonction séparée pour le badge des produits
function updateProduitsBadge() {
    fetch('/produits/count', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const produitsBadge = document.getElementById('produits-badge');
            if (produitsBadge) {
                produitsBadge.textContent = data.count;
                produitsBadge.style.display = data.count > 0 ? 'inline' : 'none';
            }
        }
    })
    .catch(error => console.error('Erreur:', error));
}

// Fonction pour ajouter les attributs data-translate aux éléments
function addTranslationAttributes() {
    // Sidebar
    const sidebarElements = {
        '.sidebar-heading small': ['main_menu', 'analytics', 'administration'],
        '.nav-link:contains("Tableau de bord")': ['dashboard'],
        '.nav-link:contains("Commandes")': ['orders'],
        '.nav-link:contains("Ventes")': ['sales'],
        '.nav-link:contains("Livraisons")': ['deliveries'],
        '.nav-link:contains("Produits")': ['products'],
        '.nav-link:contains("Clients")': ['clients'],
        '.nav-link:contains("Versements")': ['payments'],
        '.nav-link:contains("Rapports")': ['reports'],
        '.nav-link:contains("Utilisateurs")': ['users'],
        '.nav-link:contains("Paramètres")': ['settings']
    };

    // Ajouter l'attribut data-translate aux éléments de la sidebar
    document.querySelectorAll('.sidebar-heading small').forEach((el, i) => {
        const keys = ['main_menu', 'analytics', 'administration'];
        if (keys[i]) el.setAttribute('data-translate', keys[i]);
    });

    document.querySelectorAll('.nav-link').forEach(el => {
        const text = el.textContent.trim();
        const keyMap = {
            'Tableau de bord': 'dashboard',
            'Commandes': 'orders',
            'Ventes': 'sales',
            'Livraisons': 'deliveries',
            'Produits': 'products',
            'Clients': 'clients',
            'Versements': 'payments',
            'Rapports': 'reports',
            'Utilisateurs': 'users',
            'Paramètres': 'settings'
        };

        if (keyMap[text]) {
            el.setAttribute('data-translate', keyMap[text]);
        }
    });
}

function loadSavedSettings() {
    // Charger le thème sauvegardé
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        const themeCard = document.querySelector(`.theme-card[data-theme="${savedTheme}"]`);
        if (themeCard) {
            document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
            themeCard.classList.add('active');
            applyTheme(savedTheme);
        }
    }

    // Charger la langue sauvegardée
    const savedLanguage = localStorage.getItem('language');
    if (savedLanguage) {
        const langOption = document.querySelector(`.language-option[data-lang="${savedLanguage}"]`);
        if (langOption) {
            document.querySelectorAll('.language-option').forEach(o => o.classList.remove('active'));
            langOption.classList.add('active');
        }
    }

    // Charger les paramètres des switchs
    const ignoreAuth = localStorage.getItem('ignore_auth');
    if (ignoreAuth !== null) {
        document.getElementById('ignore-auth').checked = ignoreAuth === 'true';
    }

    const systemIcon = localStorage.getItem('system_icon');
    if (systemIcon !== null) {
        document.getElementById('system-icon').checked = systemIcon === 'true';
    }

    const emailNotif = localStorage.getItem('email_notif');
    if (emailNotif !== null) {
        document.getElementById('email-notif').checked = emailNotif === 'true';
    }

    const approvalRequired = localStorage.getItem('approval_required');
    if (approvalRequired !== null) {
        document.getElementById('approval-required').checked = approvalRequired === 'true';
    }

    const stockAlerts = localStorage.getItem('stock_alerts');
    if (stockAlerts !== null) {
        document.getElementById('stock-alerts').checked = stockAlerts === 'true';
    }
}

function resetSettings() {
    if (confirm('Voulez-vous vraiment restaurer tous les paramètres par défaut ?')) {
        // Restaurer les switchs
        document.querySelectorAll('.switch input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });

        // Restaurer le thème
        document.querySelectorAll('.theme-card').forEach(card => card.classList.remove('active'));
        const clairTheme = document.querySelector('.theme-card[data-theme="clair"]');
        if (clairTheme) clairTheme.classList.add('active');
        applyTheme('clair');

        // Restaurer la langue
        document.querySelectorAll('.language-option').forEach(option => option.classList.remove('active'));
        const frLang = document.querySelector('.language-option[data-lang="fr"]');
        if (frLang) frLang.classList.add('active');
        changeLanguage('fr');

        // Sauvegarder les paramètres
        saveSettingsToLocalStorage();

        showNotification('Paramètres restaurés', 'Tous les paramètres ont été restaurés par défaut', 'success');
    }
}

function cancelSettings() {
    if (confirm('Annuler toutes les modifications non enregistrées ?')) {
        // Recharger les paramètres sauvegardés
        loadSavedSettings();
        navigateTo('dashboard');
    }
}

function saveSettings() {
    const settings = {
        theme: document.querySelector('.theme-card.active')?.getAttribute('data-theme') || 'clair',
        language: document.querySelector('.language-option.active')?.getAttribute('data-lang') || 'fr',
        ignoreAuth: document.getElementById('ignore-auth').checked,
        systemIcon: document.getElementById('system-icon').checked,
        emailNotifications: document.getElementById('email-notif').checked,
        approvalRequired: document.getElementById('approval-required').checked,
        stockAlerts: document.getElementById('stock-alerts').checked
    };

    // Sauvegarder dans localStorage
    localStorage.setItem('theme', settings.theme);
    localStorage.setItem('language', settings.language);
    localStorage.setItem('ignore_auth', settings.ignoreAuth);
    localStorage.setItem('system_icon', settings.systemIcon);
    localStorage.setItem('email_notif', settings.emailNotifications);
    localStorage.setItem('approval_required', settings.approvalRequired);
    localStorage.setItem('stock_alerts', settings.stockAlerts);

    // Ici vous pouvez aussi envoyer les paramètres au serveur via AJAX
    saveSettingsToServer(settings);

    // Appliquer les changements
    applyTheme(settings.theme);
    changeLanguage(settings.language);

    showNotification('Paramètres enregistrés', 'Vos paramètres ont été sauvegardés avec succès', 'success');
    navigateTo('dashboard');
}


function saveSettingsToLocalStorage() {
    const settings = {
        theme: document.querySelector('.theme-card.active')?.getAttribute('data-theme') || 'clair',
        language: document.querySelector('.language-option.active')?.getAttribute('data-lang') || 'fr',
        ignoreAuth: document.getElementById('ignore-auth').checked,
        systemIcon: document.getElementById('system-icon').checked,
        emailNotifications: document.getElementById('email-notif').checked,
        approvalRequired: document.getElementById('approval-required').checked,
        stockAlerts: document.getElementById('stock-alerts').checked
    };

    localStorage.setItem('theme', settings.theme);
    localStorage.setItem('language', settings.language);
    localStorage.setItem('ignore_auth', settings.ignoreAuth);
    localStorage.setItem('system_icon', settings.systemIcon);
    localStorage.setItem('email_notif', settings.emailNotifications);
    localStorage.setItem('approval_required', settings.approvalRequired);
    localStorage.setItem('stock_alerts', settings.stockAlerts);
}

function saveSettingsToServer(settings) {
    // Envoyer les paramètres au serveur si nécessaire
    fetch('/admin/settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Erreur lors de la sauvegarde serveur:', data.message);
        }
    })
    .catch(error => console.error('Erreur:', error));
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
