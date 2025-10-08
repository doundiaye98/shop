<?php
// Page d'administration moderne - Gestion complète du site
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: backend/login.php');
    exit;
}

require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Ma Boutique</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin-modern.css">
    <link rel="stylesheet" href="admin-theme-harmonise.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="bi bi-shield-check me-2"></i>Administration</h3>
            <button class="btn-close-sidebar d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="#dashboard" class="nav-link active" data-section="dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#products" class="nav-link" data-section="products">
                        <i class="bi bi-box-seam"></i>
                        <span>Produits</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#orders" class="nav-link" data-section="orders">
                        <i class="bi bi-cart-check"></i>
                        <span>Commandes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#customers" class="nav-link" data-section="customers">
                        <i class="bi bi-people"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#messages" class="nav-link" data-section="messages">
                        <i class="bi bi-chat-dots"></i>
                        <span>Messages</span>
                        <span class="badge bg-danger ms-auto" id="messagesBadge" style="display: none;">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#analytics" class="nav-link" data-section="analytics">
                        <i class="bi bi-graph-up"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#settings" class="nav-link" data-section="settings">
                        <i class="bi bi-gear"></i>
                        <span>Paramètres</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-details">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></div>
                    <div class="user-role">Administrateur</div>
                </div>
            </div>
            <a href="backend/logout.php" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span>Déconnexion</span>
            </a>
            <div class="doucoder-credit" style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <p style="font-size: 0.8em; color: #64748b; margin: 0;">
                    <i class="bi bi-code-slash"></i> 
                    Créé par 
                    <span style="color: var(--secondary-color); font-weight: 600;">DOUCODER</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-main" id="mainContent">
        <!-- Top Bar -->
        <div class="admin-topbar">
            <div class="topbar-left">
                <button class="btn-toggle-sidebar d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h2 class="page-title">Tableau de bord</h2>
            </div>
            <div class="topbar-right">
                <div class="notifications">
                    <button class="btn-notification" onclick="showNotifications()">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                </div>
                <div class="search-box">
                    <input type="text" placeholder="Rechercher..." class="form-control">
                    <i class="bi bi-search"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="admin-content">
            <!-- Stats Cards -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" id="totalProducts">-</div>
                        <div class="stat-label">Produits</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>+12%</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" id="totalOrders">-</div>
                        <div class="stat-label">Commandes</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>+8%</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" id="totalCustomers">-</div>
                        <div class="stat-label">Clients</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>+15%</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-currency-euro"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" id="totalRevenue">-</div>
                        <div class="stat-label">Revenus</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>+23%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h4>Évolution des clients (12 derniers mois)</h4>
                                <div class="chart-controls">
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshClientEvolution()">
                                        <i class="bi bi-arrow-clockwise"></i> Actualiser
                                    </button>
                                </div>
                            </div>
                            <div class="chart-body">
                                <canvas id="clientEvolutionChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h4>Catégories populaires</h4>
                            </div>
                            <div class="chart-body">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h4>Ventes des 30 derniers jours</h4>
                                <div class="chart-controls">
                                    <select class="form-select form-select-sm">
                                        <option>30 jours</option>
                                        <option>7 jours</option>
                                        <option>90 jours</option>
                                    </select>
                                </div>
                            </div>
                            <div class="chart-body">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h4>Statut des commandes</h4>
                            </div>
                            <div class="chart-body">
                                <canvas id="ordersStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestion des Produits -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4><i class="bi bi-box-seam"></i> Gestion des Produits</h4>
                            <button class="btn btn-primary" onclick="addNewProduct()">
                                <i class="bi bi-plus-circle"></i> Ajouter un produit
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Catégorie</th>
                                            <th>Prix</th>
                                            <th>Stock</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productsTable">
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="loading-indicator">
                                                    <i class="bi bi-hourglass-split"></i>
                                                    Chargement des produits...
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="activity-card">
                            <div class="card-header">
                                <h4>Activité récente</h4>
                                <a href="#" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                            <div class="card-body">
                                <div class="activity-list" id="activityList">
                                    <!-- Activités chargées dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="activity-card">
                            <div class="card-header">
                                <h4>Commandes récentes</h4>
                                <a href="#" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                            <div class="card-body">
                                <div class="orders-list" id="recentOrders">
                                    <!-- Commandes chargées dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php include 'admin_modals_modern.php'; ?>
    
    <!-- Modal Notifications -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationsModalLabel">
                        <i class="bi bi-bell"></i> Notifications
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                            <i class="bi bi-check-all"></i> Tout marquer comme lu
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="notificationsList" class="notifications-list">
                        <!-- Les notifications seront chargées ici -->
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-success" onclick="addNotification('Test', 'Ceci est une notification de test', 'info')">
                            <i class="bi bi-plus-circle"></i> Ajouter une notification de test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Détails Commande -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">
                        <i class="bi bi-receipt"></i> Détails de la commande #<span id="orderId">-</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Informations générales -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-info-circle"></i> Informations générales</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>ID:</strong></div>
                                        <div class="col-8">#<span id="orderId">-</span></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Statut:</strong></div>
                                        <div class="col-8"><span id="orderStatus" class="badge">-</span></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Date:</strong></div>
                                        <div class="col-8" id="orderDate">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Total:</strong></div>
                                        <div class="col-8" id="orderTotal">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations client -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-person"></i> Informations client</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Nom:</strong></div>
                                        <div class="col-8" id="customerName">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Email:</strong></div>
                                        <div class="col-8" id="customerEmail">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Téléphone:</strong></div>
                                        <div class="col-8" id="customerPhone">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Adresse de livraison -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-geo-alt"></i> Adresse de livraison</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Adresse:</strong></div>
                                        <div class="col-8" id="shippingAddress">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Ville:</strong></div>
                                        <div class="col-8" id="shippingCity">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Code postal:</strong></div>
                                        <div class="col-8" id="shippingPostal">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Pays:</strong></div>
                                        <div class="col-8" id="shippingCountry">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations de paiement -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-credit-card"></i> Informations de paiement</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Méthode:</strong></div>
                                        <div class="col-8" id="paymentMethod">-</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>ID Paiement:</strong></div>
                                        <div class="col-8" id="paymentIntent">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Articles de la commande -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6><i class="bi bi-box"></i> Articles de la commande</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItemsTable">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Chargement...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="bi bi-chat-text"></i> Notes</h6>
                        </div>
                        <div class="card-body">
                            <p id="orderNotes" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="updateOrderStatus()">
                        <i class="bi bi-pencil"></i> Modifier le statut
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin_messaging.js"></script>
    
    <!-- Attendre que Chart.js soit chargé -->
    <script>
        // Vérifier que Chart.js est chargé
        function waitForChart() {
            if (typeof Chart === 'undefined') {
                console.log('En attente de Chart.js...');
                setTimeout(waitForChart, 100);
            } else {
                console.log('Chart.js chargé avec succès');
                initializeDashboard();
            }
        }
        
        // Timeout de sécurité
        let chartTimeout = setTimeout(function() {
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js n\'a pas pu être chargé, initialisation sans graphiques');
                // Créer un objet Chart factice pour éviter les erreurs
                window.Chart = function() {
                    console.warn('Chart.js non disponible');
                };
                initializeDashboard();
            }
        }, 5000);
        
        // Démarrer l'initialisation
        document.addEventListener('DOMContentLoaded', function() {
            waitForChart();
            // Initialiser les notifications
            updateNotificationsDisplay();
        });
    </script>
    
    <script>
        // Variables globales
        let currentSection = 'dashboard';
        
        // Fonctions d'initialisation
        function initializeDashboard() {
            console.log('Dashboard initialisé');
            setupNavigation();
            loadSection('dashboard');
        }
        
        function loadStats() {
            console.log('Chargement des statistiques...');
        }
        
        function loadCharts() {
            console.log('Chargement des graphiques...');
        }
        
        function loadRecentActivity() {
            console.log('Chargement de l\'activité récente...');
        }
        
        function loadRecentOrders() {
            console.log('Chargement des commandes récentes...');
            fetch('admin_api.php?action=get_recent_orders')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRecentOrders(data.orders);
                    } else {
                        console.error('Erreur chargement commandes:', data.message);
                        document.getElementById('recentOrders').innerHTML = '<p class="text-muted">Erreur lors du chargement des commandes</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement commandes:', error);
                    document.getElementById('recentOrders').innerHTML = '<p class="text-muted">Erreur de connexion</p>';
                });
        }
        
        function displayRecentOrders(orders) {
            const container = document.getElementById('recentOrders');
            if (!container) return;
            
            if (!orders || orders.length === 0) {
                container.innerHTML = '<p class="text-muted">Aucune commande récente</p>';
                return;
            }
            
            container.innerHTML = orders.map(order => `
                <div class="order-item">
                    <div class="order-header">
                        <div class="order-info">
                            <h6>Commande #${order.id}</h6>
                            <span class="order-customer">${order.customer_name || 'Client inconnu'}</span>
                        </div>
                        <div class="order-status">
                            <span class="badge bg-${getStatusColor(order.status)}">${order.status}</span>
                        </div>
                    </div>
                    <div class="order-details">
                        <div class="order-total">${formatCurrency(order.total)}</div>
                        <div class="order-date">${formatTime(order.created_at)}</div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(${order.id})">
                            <i class="bi bi-eye"></i> Voir
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateOrderStatus(${order.id})">
                            <i class="bi bi-pencil"></i> Modifier
                        </button>
                    </div>
                </div>
            `).join('');
        }
        
        // Système de navigation par sections
        function setupNavigation() {
            const navLinks = document.querySelectorAll('.nav-link[data-section]');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const section = this.getAttribute('data-section');
                    if (section) {
                        loadSection(section);
                        updateActiveNav(this);
                    }
                });
            });
        }
        
        function loadSection(section) {
            currentSection = section;
            const content = document.querySelector('.admin-content');
            
            // Mettre à jour le titre de la page
            const pageTitle = document.querySelector('.page-title');
            const titles = {
                'dashboard': 'Tableau de bord',
                'products': 'Gestion des produits',
                'orders': 'Commandes',
                'customers': 'Clients',
                'messages': 'Messagerie',
                'analytics': 'Analytics',
                'settings': 'Paramètres'
            };
            
            if (pageTitle && titles[section]) {
                pageTitle.textContent = titles[section];
            }
            
            // Charger le contenu de la section
            loadSectionContent(section);
        }
        
        function updateActiveNav(activeLink) {
            // Retirer la classe active de tous les liens
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Ajouter la classe active au lien cliqué
            activeLink.classList.add('active');
        }
        
        function loadSectionContent(section) {
            const content = document.querySelector('.admin-content');
            
            switch(section) {
                case 'dashboard':
                    loadDashboardContent();
                    break;
                case 'products':
                    loadProductsContent();
                    break;
                case 'orders':
                    loadOrdersContent();
                    break;
                case 'customers':
                    loadCustomersContent();
                    break;
                case 'messages':
                    loadMessagesContent();
                    break;
                case 'analytics':
                    loadAnalyticsContent();
                    break;
                case 'settings':
                    loadSettingsContent();
                    break;
            }
        }
        
        function loadDashboardContent() {
            // Le contenu du dashboard est déjà chargé dans le HTML
            loadDynamicData();
        }
        
        function loadProductsContent() {
            const content = document.querySelector('.admin-content');
            content.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Gestion des produits</h2>
                            <button class="btn btn-primary" onclick="addNewProduct()">
                                <i class="bi bi-plus"></i> Ajouter un produit
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="productsTableSection">
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
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="loading-indicator">
                                                        <i class="bi bi-hourglass-split"></i>
                                                        Chargement des produits...
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            loadProductsTableSection();
        }
        
        function loadOrdersContent() {
            const content = document.querySelector('.admin-content');
            content.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4">Gestion des commandes</h2>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="ordersTableSection">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Client</th>
                                                <th>Total</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="loading-indicator">
                                                        <i class="bi bi-hourglass-split"></i>
                                                        Chargement des commandes...
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            loadOrdersTableSection();
        }
        
        function loadCustomersContent() {
            const content = document.querySelector('.admin-content');
            content.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4">Gestion des clients</h2>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="customersTableSection">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                                <th>Commandes</th>
                                                <th>Inscription</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="loading-indicator">
                                                        <i class="bi bi-hourglass-split"></i>
                                                        Chargement des clients...
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            loadCustomersTableSection();
        }
        
        function loadAnalyticsContent() {
            const content = document.querySelector('.admin-content');
            content.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4">Analytics avancées</h2>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Performance des produits</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="productPerformanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Évolution des revenus</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="revenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function loadSettingsContent() {
            const content = document.querySelector('.admin-content');
            content.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4">Paramètres du site</h2>
                        <div class="card">
                            <div class="card-body">
                                <form id="settingsForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom du site</label>
                                                <input type="text" class="form-control" name="site_name" value="Ma Boutique">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email de contact</label>
                                                <input type="email" class="form-control" name="contact_email" value="contact@maboutique.com">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Téléphone</label>
                                                <input type="tel" class="form-control" name="phone" value="+33 1 23 45 67 89">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Devise</label>
                                                <select class="form-select" name="currency">
                                                    <option value="EUR" selected>Euro (€)</option>
                                                    <option value="USD">Dollar ($)</option>
                                                    <option value="GBP">Livre (£)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description du site</label>
                                        <textarea class="form-control" name="description" rows="3">Votre boutique en ligne de vêtements pour enfants</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Initialiser le dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            loadStats();
            loadCharts();
            loadRecentActivity();
            loadRecentOrders();
            loadProductsTable(); // Charger les produits au démarrage
            loadDynamicData(); // Charger toutes les données dynamiques
        });
        
        // Charger les données dynamiques depuis l'API existante
        function loadDynamicData() {
            console.log('Chargement des données dynamiques...');
            
            // Charger les statistiques
            fetch('admin_api.php?action=get_stats')
                .then(response => response.json())
                .then(data => {
                    console.log('Statistiques reçues:', data);
                    if (data.success) {
                        updateStatsCards(data.stats);
                    } else {
                        console.error('Erreur statistiques:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement statistiques:', error);
                });
            
            // Charger les notifications depuis le backend
            loadNotifications();
            
            // Charger les utilisateurs récents
            fetch('admin_api.php?action=get_users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRecentUsers(data.users.slice(0, 5));
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement utilisateurs:', error);
                });
            
            // Charger les produits récents
            fetch('admin_api.php?action=get_products')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRecentProducts(data.products.slice(0, 5));
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement produits:', error);
                });
            
            // Charger les utilisateurs en ligne
            fetch('admin_api.php?action=get_online_users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateOnlineUsers(data.users);
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement utilisateurs en ligne:', error);
                });
            
            // Charger l'évolution des clients
            loadClientEvolutionChart();
        }
        
        // Charger le diagramme d'évolution des clients
        function loadClientEvolutionChart() {
            fetch('admin_api.php?action=get_client_evolution')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderClientEvolutionChart(data.data);
                    } else {
                        console.error('Erreur chargement évolution clients:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement évolution clients:', error);
                });
        }
        
        // Rendre le diagramme d'évolution des clients
        function renderClientEvolutionChart(chartData) {
            const ctx = document.getElementById('clientEvolutionChart');
            if (!ctx) return;
            
            // Vérifier que Chart.js est disponible
            if (typeof Chart === 'undefined') {
                console.error('Chart.js n\'est pas disponible');
                return;
            }
            
            // Détruire le graphique existant s'il existe
            if (window.clientEvolutionChartInstance) {
                window.clientEvolutionChartInstance.destroy();
            }
            
            window.clientEvolutionChartInstance = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
        
        // Actualiser le diagramme d'évolution des clients
        function refreshClientEvolution() {
            loadClientEvolutionChart();
            showNotification('Diagramme d\'évolution actualisé', 'success');
        }
        
        // Mettre à jour les cartes de statistiques avec les vraies données
        function updateStatsCards(stats) {
            console.log('Mise à jour des statistiques:', stats);
            
            // Mapper les données de l'API existante vers les nouveaux éléments
            const elements = {
                'totalProducts': stats.total_products || stats.total_products || 0,
                'totalOrders': stats.total_orders || stats.total_orders || 0,
                'totalCustomers': stats.total_users || stats.total_customers || 0,
                'totalRevenue': formatCurrency(stats.total_revenue || stats.total_revenue || 0)
            };
            
            Object.entries(elements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) {
                    animateNumber(element, value);
                }
            });
        }
        
        // Afficher les utilisateurs récents
        function displayRecentUsers(users) {
            const container = document.getElementById('activityList');
            if (!container) return;
            
            container.innerHTML = users.map(user => `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Nouvel utilisateur: ${user.username}</div>
                        <div class="activity-time">${formatTime(user.created_at)}</div>
                    </div>
                </div>
            `).join('');
        }
        
        // Afficher les produits récents
        function displayRecentProducts(products) {
            const container = document.getElementById('recentOrders');
            if (!container) return;
            
            container.innerHTML = products.map(product => `
                <div class="order-item">
                    <div class="order-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="order-content">
                        <div class="order-title">${product.name}</div>
                        <div class="order-time">${formatTime(product.created_at)}</div>
                    </div>
                    <div class="order-status completed">${product.category}</div>
                </div>
            `).join('');
        }
        
        // Mettre à jour les utilisateurs en ligne
        function updateOnlineUsers(users) {
            console.log('Utilisateurs en ligne:', users);
            // Vous pouvez ajouter un indicateur d'utilisateurs en ligne dans l'interface
        }
        
        // Fonctions utilitaires pour le formatage
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount);
        }
        
        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'À l\'instant';
            if (diff < 3600000) return `${Math.floor(diff / 60000)} min`;
            if (diff < 86400000) return `${Math.floor(diff / 3600000)} h`;
            return date.toLocaleDateString('fr-FR');
        }
        
        function animateNumber(element, targetValue) {
            const startValue = parseInt(element.textContent) || 0;
            const isCurrency = targetValue.toString().includes('€');
            const numericValue = isCurrency ? parseFloat(targetValue.replace(/[^\d.,]/g, '').replace(',', '.')) : parseInt(targetValue);
            
            let currentValue = startValue;
            const increment = (numericValue - startValue) / 50;
            const timer = setInterval(() => {
                currentValue += increment;
                if ((increment > 0 && currentValue >= numericValue) || (increment < 0 && currentValue <= numericValue)) {
                    currentValue = numericValue;
                    clearInterval(timer);
                }
                
                if (isCurrency) {
                    element.textContent = formatCurrency(currentValue);
                } else {
                    element.textContent = Math.floor(currentValue).toLocaleString('fr-FR');
                }
            }, 20);
        }
        
        // Gestion dynamique des produits
        function loadProductsTable() {
            fetch('admin_api.php?action=get_products')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProductsTable(data.products);
                    }
                })
                .catch(error => console.error('Erreur chargement produits:', error));
        }
        
        function displayProductsTable(products) {
            const tbody = document.querySelector('#productsTable tbody');
            if (!tbody) return;
            
            tbody.innerHTML = products.map(product => `
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="${product.image || 'assets/images/no-image.png'}" alt="${product.name}" class="product-thumb">
                            <span>${product.name}</span>
                        </div>
                    </td>
                    <td>${product.category}</td>
                    <td>${formatCurrency(product.price)}</td>
                    <td>
                        <span class="badge ${product.stock > 0 ? 'badge-success' : 'badge-danger'}">
                            ${product.stock} en stock
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        // Gestion dynamique des utilisateurs
        function loadUsersTable() {
            fetch('admin_api.php?action=get_users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayUsersTable(data.users);
                    }
                })
                .catch(error => console.error('Erreur chargement utilisateurs:', error));
        }
        
        function displayUsersTable(users) {
            const tbody = document.querySelector('#usersTable tbody');
            if (!tbody) return;
            
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div>
                                <div class="user-name">${user.username}</div>
                                <div class="user-email">${user.email}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge ${user.role === 'admin' ? 'badge-primary' : 'badge-secondary'}">
                            ${user.role}
                        </span>
                    </td>
                    <td>${formatTime(user.created_at)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary" onclick="editUser(${user.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        // Fonctions CRUD pour les produits
        function openProductModal(product = null) {
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            const form = document.getElementById('productForm');
            
            if (product) {
                // Mode édition
                document.getElementById('productModalLabel').textContent = 'Modifier le produit';
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name || '';
                document.getElementById('productCategory').value = product.category || '';
                document.getElementById('productPrice').value = product.price || '';
                document.getElementById('productStock').value = product.stock || 0;
                document.getElementById('productDescription').value = product.description || '';
                document.getElementById('productPromoPrice').value = product.promo_price || '';
            } else {
                // Mode ajout
                document.getElementById('productModalLabel').textContent = 'Ajouter un produit';
                form.reset();
                document.getElementById('productId').value = '';
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('previewContainer').innerHTML = '';
            }
            
            // Attacher l'événement pour l'aperçu des images
            setupImagePreview();
            
            modal.show();
        }
        
        // Configurer l'aperçu des images
        function setupImagePreview() {
            const imageInput = document.getElementById('productImages');
            if (imageInput) {
                // Supprimer l'ancien événement s'il existe
                imageInput.removeEventListener('change', handleImagePreview);
                // Ajouter le nouvel événement
                imageInput.addEventListener('change', handleImagePreview);
            }
        }
        
        // Gestionnaire pour l'aperçu des images
        function handleImagePreview(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            
            previewContainer.innerHTML = '';
            
            if (files.length > 0) {
                imagePreview.style.display = 'block';
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'position-relative';
                            previewDiv.innerHTML = `
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="removeImagePreview(${index})" style="transform: translate(50%, -50%);">
                                    <i class="bi bi-x"></i>
                                </button>
                            `;
                            previewContainer.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                imagePreview.style.display = 'none';
            }
        }
        
        function editProduct(id) {
            fetch(`admin_api.php?action=get_product&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        openProductModal(data.product);
                    } else {
                        showNotification('Erreur lors du chargement du produit', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Erreur de connexion', 'error');
                });
        }
        
        function saveProduct() {
            const form = document.getElementById('productForm');
            const formData = new FormData(form);
            
            // Ajouter l'action
            const productId = document.getElementById('productId').value;
            formData.append('action', productId ? 'update_product' : 'add_product');
            if (productId) {
                formData.append('product_id', productId);
            }
            
            // Validation
            const name = formData.get('name');
            const category = formData.get('category');
            const price = formData.get('price');
            
            if (!name || !category || !price || price <= 0) {
                showNotification('Veuillez remplir tous les champs obligatoires', 'error');
                return;
            }
            
            // Afficher un indicateur de chargement
            const saveBtn = document.querySelector('#productModal .btn-primary');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sauvegarde...';
            saveBtn.disabled = true;
            
            fetch('admin_api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                    loadProductsTable();
                    loadDynamicData(); // Recharger les stats
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Erreur lors de la sauvegarde', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }
        
        function deleteProduct(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                const formData = new FormData();
                formData.append('action', 'delete_product');
                formData.append('id', id);
                
                fetch('admin_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Produit supprimé avec succès', 'success');
                        loadProductsTable();
                        loadDynamicData(); // Recharger les stats
                    } else {
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                });
            }
        }
        
        // Fonctions CRUD pour les utilisateurs
        function editUser(id) {
            fetch(`admin_api.php?action=get_user&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        openUserModal(data.user);
                    }
                });
        }
        
        function deleteUser(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                const formData = new FormData();
                formData.append('action', 'delete_user');
                formData.append('id', id);
                
                fetch('admin_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Utilisateur supprimé avec succès', 'success');
                        loadUsersTable();
                        loadDynamicData(); // Recharger les stats
                    } else {
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                });
            }
        }
        
        // Système de notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
        
        // Système de notifications
        let notifications = [
            {
                id: 1,
                title: 'Nouvelle commande',
                message: 'Commande #001 reçue de Jean Dupont',
                type: 'success',
                time: 'Il y a 5 minutes',
                read: false
            },
            {
                id: 2,
                title: 'Stock faible',
                message: 'Le produit "T-shirt bleu" est en rupture de stock',
                type: 'warning',
                time: 'Il y a 1 heure',
                read: false
            },
            {
                id: 3,
                title: 'Nouvel utilisateur',
                message: 'Marie Martin s\'est inscrite sur le site',
                type: 'info',
                time: 'Il y a 2 heures',
                read: false
            }
        ];
        
        // Fonction pour afficher les notifications (bouton cloche)
        function showNotifications() {
            const modal = new bootstrap.Modal(document.getElementById('notificationsModal'));
            updateNotificationsDisplay();
            modal.show();
        }
        
        // Mettre à jour l'affichage des notifications
        function updateNotificationsDisplay() {
            const unreadCount = notifications.filter(n => !n.read).length;
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = unreadCount;
                badge.style.display = unreadCount > 0 ? 'block' : 'none';
            }
            
            // Mettre à jour la liste des notifications
            const notificationsList = document.getElementById('notificationsList');
            if (notificationsList) {
                notificationsList.innerHTML = notifications.map(notification => `
                    <div class="notification-item ${notification.read ? 'read' : 'unread'}" onclick="markAsRead(${notification.id})">
                        <div class="notification-icon">
                            <i class="bi bi-${getNotificationIcon(notification.type)}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.time}</div>
                        </div>
                        ${!notification.read ? '<div class="notification-dot"></div>' : ''}
                    </div>
                `).join('');
            }
        }
        
        // Obtenir l'icône selon le type de notification
        function getNotificationIcon(type) {
            switch(type) {
                case 'success': return 'check-circle-fill';
                case 'warning': return 'exclamation-triangle-fill';
                case 'error': return 'x-circle-fill';
                case 'info': 
                default: return 'info-circle-fill';
            }
        }
        
        // Marquer une notification comme lue
        function markAsRead(id) {
            const notification = notifications.find(n => n.id === id);
            if (notification && !notification.read) {
                notification.read = true;
                updateNotificationsDisplay();
                // Marquer comme lue dans le backend
                markNotificationAsRead(id);
            }
        }
        
        // Marquer toutes les notifications comme lues
        function markAllAsRead() {
            fetch('admin_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'mark_all_notifications_read'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Marquer toutes les notifications comme lues localement
                    notifications.forEach(n => n.read = true);
                    updateNotificationsDisplay();
                    showNotification('Toutes les notifications marquées comme lues', 'success');
                } else {
                    showNotification('Erreur: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur marquage toutes notifications:', error);
                showNotification('Erreur lors du marquage', 'error');
            });
        }
        
        // Ajouter une nouvelle notification
        function addNotification(title, message, type = 'info') {
            const newNotification = {
                id: Date.now(),
                title: title,
                message: message,
                type: type,
                time: 'Maintenant',
                read: false
            };
            notifications.unshift(newNotification);
            updateNotificationsDisplay();
        }
        
        // Charger les notifications depuis le backend
        function loadNotifications() {
            fetch('admin_api.php?action=get_notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.notifications) {
                        // Fusionner avec les notifications existantes
                        const backendNotifications = data.notifications.map(notif => ({
                            id: notif.id,
                            title: notif.title,
                            message: notif.message,
                            type: notif.type,
                            time: notif.time,
                            read: notif.read === 1
                        }));
                        
                        // Remplacer les notifications de test par celles du backend
                        notifications = backendNotifications;
                        updateNotificationsDisplay();
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement notifications:', error);
                });
        }
        
        // Marquer une notification comme lue dans le backend
        function markNotificationAsRead(id) {
            fetch('admin_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'mark_notification_read',
                    notification_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notification marquée comme lue');
                }
            })
            .catch(error => {
                console.error('Erreur marquage notification:', error);
            });
        }
        
        // L'événement pour l'aperçu des images est maintenant géré dans setupImagePreview()
        
        function removeImagePreview(index) {
            const input = document.getElementById('productImages');
            const dt = new DataTransfer();
            
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            
            // Re-trigger l'événement change
            const event = new Event('change', { bubbles: true });
            input.dispatchEvent(event);
        }
        
        // Fonction pour ajouter un nouveau produit
        function addNewProduct() {
            console.log('Bouton ajouter produit cliqué');
            
            // Vérifier si le modal existe
            const modal = document.getElementById('productModal');
            if (!modal) {
                console.error('Modal productModal non trouvé !');
                alert('Erreur: Modal d\'ajout de produit non trouvé. Vérifiez que admin_modals_modern.php est inclus.');
                return;
            }
            
            // Vérifier si Bootstrap est chargé
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap non chargé !');
                alert('Erreur: Bootstrap n\'est pas chargé. Le modal ne peut pas s\'ouvrir.');
                return;
            }
            
            // Ouvrir le modal
            openProductModal();
        }
        
        // Fonction globale pour ouvrir le modal (accessible depuis les boutons)
        window.addNewProduct = addNewProduct;
        
        // Charger les tables au changement d'onglet
        document.addEventListener('click', function(e) {
            if (e.target.getAttribute('data-bs-target') === '#products') {
                setTimeout(loadProductsTable, 100);
            } else if (e.target.getAttribute('data-bs-target') === '#users') {
                setTimeout(loadUsersTable, 100);
            }
        });
        
        // Actualisation automatique des données toutes les 30 secondes
        setInterval(loadDynamicData, 30000);
        
        // Fonctions pour charger les données des sections
        function loadProductsTableSection() {
            fetch('admin_api.php?action=get_products')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProductsTableSection(data.products);
                    } else {
                        console.error('Erreur chargement produits:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement produits:', error);
                });
        }
        
        function displayProductsTableSection(products) {
            const tbody = document.querySelector('#productsTableSection tbody');
            if (!tbody) return;
            
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun produit trouvé</td></tr>';
                return;
            }
            
            tbody.innerHTML = products.map(product => `
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="${product.image || 'assets/images/no-image.png'}" alt="${product.name}" class="product-thumb">
                            <span>${product.name}</span>
                        </div>
                    </td>
                    <td>${product.name}</td>
                    <td>${product.category}</td>
                    <td>${formatCurrency(product.price)}</td>
                    <td>
                        <span class="badge ${product.stock > 0 ? 'badge-success' : 'badge-danger'}">
                            ${product.stock} en stock
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        function loadOrdersTableSection() {
            fetch('admin_api.php?action=get_orders')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayOrdersTableSection(data.orders);
                    } else {
                        console.error('Erreur chargement commandes:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement commandes:', error);
                });
        }
        
        function displayOrdersTableSection(orders) {
            const tbody = document.querySelector('#ordersTableSection tbody');
            if (!tbody) return;
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune commande trouvée</td></tr>';
                return;
            }
            
            tbody.innerHTML = orders.map(order => `
                <tr>
                    <td>#${order.id}</td>
                    <td>${order.customer_name || 'N/A'}</td>
                    <td>${formatCurrency(order.total || 0)}</td>
                    <td>
                        <span class="badge bg-${getStatusColor(order.status)}">${order.status}</span>
                    </td>
                    <td>${formatTime(order.created_at)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary" onclick="viewOrder(${order.id})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id})">
                                <i class="bi bi-check"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        function loadCustomersTableSection() {
            fetch('admin_api.php?action=get_users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCustomersTableSection(data.users);
                    } else {
                        console.error('Erreur chargement clients:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement clients:', error);
                });
        }
        
        function displayCustomersTableSection(customers) {
            const tbody = document.querySelector('#customersTableSection tbody');
            if (!tbody) return;
            
            if (customers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun client trouvé</td></tr>';
                return;
            }
            
            tbody.innerHTML = customers.map(customer => `
                <tr>
                    <td>${customer.username}</td>
                    <td>${customer.email}</td>
                    <td>${customer.phone || '-'}</td>
                    <td>${customer.order_count || 0}</td>
                    <td>${formatTime(customer.created_at)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary" onclick="viewCustomer(${customer.id})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="editCustomer(${customer.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        function getStatusColor(status) {
            const colors = {
                'pending': 'warning',
                'processing': 'info',
                'shipped': 'primary',
                'delivered': 'success',
                'cancelled': 'danger'
            };
            return colors[status] || 'secondary';
        }
        
        // Fonctions d'action (simplifiées)
        function editProduct(id) {
            console.log('Édition produit:', id);
            showNotification('Fonction d\'édition en cours de développement', 'info');
        }
        
        function deleteProduct(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                console.log('Suppression produit:', id);
                showNotification('Fonction de suppression en cours de développement', 'info');
            }
        }
        
        function viewOrder(id) {
            console.log('Voir commande:', id);
            loadOrderDetails(id);
        }
        
        // Charger les détails d'une commande
        function loadOrderDetails(orderId) {
            fetch(`admin_api.php?action=get_order_details&id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayOrderModal(data.order);
                    } else {
                        showNotification('Erreur lors du chargement de la commande: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur chargement commande:', error);
                    showNotification('Erreur de connexion', 'error');
                });
        }
        
        // Afficher le modal de détails de commande
        function displayOrderModal(order) {
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            
            // Remplir les données du modal
            document.getElementById('orderId').textContent = order.id;
            document.getElementById('orderStatus').textContent = order.status;
            document.getElementById('orderStatus').className = `badge bg-${getStatusColor(order.status)}`;
            document.getElementById('orderDate').textContent = formatTime(order.created_at);
            document.getElementById('orderTotal').textContent = formatCurrency(order.total);
            
            // Informations client
            document.getElementById('customerName').textContent = order.customer_name || 'N/A';
            document.getElementById('customerEmail').textContent = order.customer_email || 'N/A';
            document.getElementById('customerPhone').textContent = order.customer_phone || 'N/A';
            
            // Adresse de livraison
            document.getElementById('shippingAddress').textContent = order.shipping_address || 'N/A';
            document.getElementById('shippingCity').textContent = order.shipping_city || 'N/A';
            document.getElementById('shippingPostal').textContent = order.shipping_postal_code || 'N/A';
            document.getElementById('shippingCountry').textContent = order.shipping_country || 'N/A';
            
            // Méthode de paiement
            document.getElementById('paymentMethod').textContent = order.payment_method || 'N/A';
            document.getElementById('paymentIntent').textContent = order.payment_intent_id || 'N/A';
            
            // Notes
            document.getElementById('orderNotes').textContent = order.notes || 'Aucune note';
            
            // Articles de la commande
            displayOrderItems(order.items || []);
            
            modal.show();
        }
        
        // Afficher les articles de la commande
        function displayOrderItems(items) {
            const tbody = document.getElementById('orderItemsTable');
            if (!tbody) return;
            
            if (items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun article</td></tr>';
                return;
            }
            
            tbody.innerHTML = items.map(item => `
                <tr>
                    <td>${item.product_name}</td>
                    <td>${item.quantity}</td>
                    <td>${formatCurrency(item.unit_price)}</td>
                    <td>${formatCurrency(item.total_price)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewProduct(${item.product_id})">
                            <i class="bi bi-eye"></i> Voir
                        </button>
                    </td>
                </tr>
            `).join('');
        }
        
        // Voir un produit (fonction simplifiée)
        function viewProduct(productId) {
            showNotification(`Visualisation du produit ID: ${productId} - Fonctionnalité à développer`, 'info');
        }
        
        // Obtenir la couleur du statut
        function getStatusColor(status) {
            const statusColors = {
                'pending': 'warning',
                'processing': 'info',
                'shipped': 'primary',
                'delivered': 'success',
                'cancelled': 'danger',
                'refunded': 'secondary'
            };
            return statusColors[status] || 'secondary';
        }
        
        function updateOrderStatus(id) {
            console.log('Mettre à jour statut:', id);
            showNotification('Fonction de mise à jour en cours de développement', 'info');
        }
        
        function viewCustomer(id) {
            console.log('Voir client:', id);
            showNotification('Fonction de visualisation en cours de développement', 'info');
        }
        
        function editCustomer(id) {
            console.log('Édition client:', id);
            showNotification('Fonction d\'édition en cours de développement', 'info');
        }
        
        // Les données seront chargées par initializeDashboard()
    </script>
</body>
</html>
