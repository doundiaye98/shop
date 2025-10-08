<?php
// Page d'administration - Gestion des utilisateurs et produits
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #28a745;
            --accent-color: #ff6b6b;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-bg) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .admin-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        .admin-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-change {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .stats-change.positive {
            color: var(--success-color);
        }

        .stats-change.negative {
            color: var(--danger-color);
        }

        .admin-tabs {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .nav-tabs {
            border: none;
            padding: 0 1.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background: transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--secondary-color);
            background: transparent;
            border-bottom: 3px solid var(--secondary-color);
        }

        .tab-content {
            padding: 2rem;
        }

        .data-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table {
            margin: 0;
        }

        .table th {
            background: var(--light-bg);
            border: none;
            font-weight: 600;
            color: var(--primary-color);
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f3f4;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-online {
            background: #d4edda;
            color: #155724;
        }

        .status-offline {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: var(--warning-color);
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
            color: #212529;
        }

        .btn-delete {
            background: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            color: white;
        }

        .btn-add {
            background: var(--success-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            background: #218838;
            color: white;
            transform: translateY(-2px);
        }

        .search-box {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-box:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .filter-dropdown {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .filter-dropdown:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .chart-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-bg) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        @media (max-width: 768px) {
            .admin-header h1 {
                font-size: 2rem;
            }
            
            .stats-card {
                padding: 1rem;
            }
            
            .stats-number {
                font-size: 1.5rem;
            }
            
            .tab-content {
                padding: 1rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
        <body>
            <!-- Header Administration -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="bi bi-gear-fill me-3"></i>Tableau de Bord</h1>
                    <p>Gérez vos utilisateurs, produits et suivez vos performances</p>
                </div>
                                       <div class="col-md-4 text-md-end">
                           <span class="badge bg-light text-dark fs-6 me-3">
                               <i class="bi bi-person-circle me-2"></i>
                               <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                           </span>
                           <a href="logout.php" class="btn btn-outline-light btn-sm">
                               <i class="bi bi-box-arrow-right me-2"></i>
                               Déconnexion
                           </a>
                       </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistiques rapides -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center">
                    <div class="stats-icon text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stats-number" id="total-users">-</div>
                    <div class="stats-label">Utilisateurs</div>
                    <div class="stats-change positive" id="users-change">+0%</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center">
                    <div class="stats-icon text-success">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div class="stats-number" id="total-products">-</div>
                    <div class="stats-label">Produits</div>
                    <div class="stats-change positive" id="products-change">+0%</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center">
                    <div class="stats-icon text-warning">
                        <i class="bi bi-cart-check-fill"></i>
                    </div>
                    <div class="stats-number" id="total-orders">-</div>
                    <div class="stats-label">Commandes</div>
                    <div class="stats-change positive" id="orders-change">+0%</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center">
                    <div class="stats-icon text-info">
                        <i class="bi bi-currency-euro"></i>
                    </div>
                    <div class="stats-number" id="total-revenue">-</div>
                    <div class="stats-label">Revenus</div>
                    <div class="stats-change positive" id="revenue-change">+0%</div>
                </div>
            </div>
        </div>

        <!-- Onglets d'administration -->
        <div class="admin-tabs">
            <ul class="nav nav-tabs" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">
                        <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                        <i class="bi bi-people me-2"></i>Utilisateurs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">
                        <i class="bi bi-box-seam me-2"></i>Produits
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                        <i class="bi bi-graph-up me-2"></i>Analyses
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="adminTabContent">
                <!-- Tableau de bord -->
                <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="chart-container">
                                <h3 class="chart-title">Évolution des ventes mensuelles</h3>
                                <canvas id="salesChart" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="chart-container">
                                <h3 class="chart-title">Répartition des catégories</h3>
                                <canvas id="categoryChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="chart-container">
                                <h3 class="chart-title">Utilisateurs en ligne</h3>
                                <div id="online-users-list">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-arrow-clockwise fs-1"></i>
                                        <p>Chargement des utilisateurs en ligne...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion des utilisateurs -->
                <div class="tab-pane fade" id="users" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3><i class="bi bi-people me-2"></i>Gestion des utilisateurs</h3>
                        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus-circle me-2"></i>Ajouter un utilisateur
                        </button>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control search-box" id="userSearch" placeholder="Rechercher un utilisateur...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter-dropdown" id="userStatusFilter">
                                <option value="">Tous les statuts</option>
                                <option value="online">En ligne</option>
                                <option value="offline">Hors ligne</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter-dropdown" id="userRoleFilter">
                                <option value="">Tous les rôles</option>
                                <option value="admin">Administrateurs</option>
                                <option value="user">Utilisateurs</option>
                            </select>
                        </div>
                    </div>

                    <div class="data-table">
                        <div class="table-responsive">
                            <table class="table table-hover" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Dernière connexion</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="bi bi-arrow-clockwise fs-1"></i>
                                            <p>Chargement des utilisateurs...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gestion des produits -->
                <div class="tab-pane fade" id="products" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3><i class="bi bi-box-seam me-2"></i>Gestion des produits</h3>
                        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-circle me-2"></i>Ajouter un produit
                        </button>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control search-box" id="productSearch" placeholder="Rechercher un produit...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter-dropdown" id="productCategoryFilter">
                                <option value="">Toutes les catégories</option>
                                <option value="bebe">Bébé</option>
                                <option value="fille">Fille</option>
                                <option value="garcon">Garçon</option>
        


                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter-dropdown" id="productStockFilter">
                                <option value="">Tous les stocks</option>
                                <option value="in">En stock</option>
                                <option value="low">Stock faible</option>
                                <option value="out">Rupture</option>
                            </select>
                        </div>
                    </div>

                    <div class="data-table">
                        <div class="table-responsive">
                            <table class="table table-hover" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="bi bi-arrow-clockwise fs-1"></i>
                                            <p>Chargement des produits...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Analyses -->
                <div class="tab-pane fade" id="analytics" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Top des produits vendus</h3>
                                <div id="top-products-list">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-arrow-clockwise fs-1"></i>
                                        <p>Chargement des statistiques...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Activité des utilisateurs</h3>
                                <div id="user-activity-list">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-arrow-clockwise fs-1"></i>
                                        <p>Chargement des statistiques...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php include 'admin_modals.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    <script src="admin_dashboard.js"></script>
    <script src="admin_product_images.js"></script>
</body>
</html>
