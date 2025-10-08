<?php
// Page de démonstration des fonctionnalités dynamiques de l'admin
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
    <title>Démonstration Admin Dynamique - Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="admin-modern.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-shield-check"></i> Admin Dynamique</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="admin_dashboard_modern.php" class="nav-item active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-box-seam"></i>
                    <span>Produits</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-people"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-cart-check"></i>
                    <span>Commandes</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <button class="sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1>Démonstration Admin Dynamique</h1>
                </div>
                <div class="top-bar-right">
                    <div class="user-menu">
                        <span>Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <a href="backend/logout.php" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Alertes d'information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h4><i class="bi bi-info-circle"></i> Fonctionnalités Dynamiques Actives</h4>
                                <p>Cette interface utilise des données réelles de votre base de données et se met à jour automatiquement.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques en temps réel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-graph-up"></i> Statistiques en Temps Réel</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="stat-card">
                                                <div class="stat-icon">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-number" id="totalProducts">-</div>
                                                    <div class="stat-label">Produits</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-card">
                                                <div class="stat-icon">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-number" id="totalUsers">-</div>
                                                    <div class="stat-label">Utilisateurs</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-card">
                                                <div class="stat-icon">
                                                    <i class="bi bi-cart-check"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-number" id="totalOrders">-</div>
                                                    <div class="stat-label">Commandes</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-card">
                                                <div class="stat-icon">
                                                    <i class="bi bi-currency-euro"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-number" id="totalRevenue">-</div>
                                                    <div class="stat-label">Chiffre d'Affaires</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions de test -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-tools"></i> Actions de Test</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button class="btn btn-primary w-100" onclick="testAPI()">
                                                <i class="bi bi-play-circle"></i> Tester l'API
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-success w-100" onclick="refreshData()">
                                                <i class="bi bi-arrow-clockwise"></i> Actualiser les Données
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-info w-100" onclick="showNotification('Test de notification', 'success')">
                                                <i class="bi bi-bell"></i> Tester les Notifications
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logs en temps réel -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-terminal"></i> Logs en Temps Réel</h5>
                                </div>
                                <div class="card-body">
                                    <div id="logs" class="logs-container">
                                        <div class="log-entry">
                                            <span class="log-time"><?= date('H:i:s') ?></span>
                                            <span class="log-message">Système initialisé</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonctions utilitaires
        function addLog(message) {
            const logsContainer = document.getElementById('logs');
            const logEntry = document.createElement('div');
            logEntry.className = 'log-entry';
            logEntry.innerHTML = `
                <span class="log-time">${new Date().toLocaleTimeString()}</span>
                <span class="log-message">${message}</span>
            `;
            logsContainer.insertBefore(logEntry, logsContainer.firstChild);
            
            // Garder seulement les 10 dernières entrées
            while (logsContainer.children.length > 10) {
                logsContainer.removeChild(logsContainer.lastChild);
            }
        }

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

        function animateNumber(element, targetValue) {
            const startValue = parseInt(element.textContent) || 0;
            const numericValue = parseInt(targetValue);
            
            let currentValue = startValue;
            const increment = (numericValue - startValue) / 50;
            const timer = setInterval(() => {
                currentValue += increment;
                if ((increment > 0 && currentValue >= numericValue) || (increment < 0 && currentValue <= numericValue)) {
                    currentValue = numericValue;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(currentValue).toLocaleString('fr-FR');
            }, 20);
        }

        // Charger les données dynamiques
        function loadDynamicData() {
            addLog('Chargement des données dynamiques...');
            
            fetch('admin_api.php?action=get_stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addLog('Statistiques chargées avec succès');
                        
                        // Mettre à jour les statistiques
                        const stats = data.stats;
                        animateNumber(document.getElementById('totalProducts'), stats.total_products || 0);
                        animateNumber(document.getElementById('totalUsers'), stats.total_users || 0);
                        animateNumber(document.getElementById('totalOrders'), stats.total_orders || 0);
                        
                        const revenueElement = document.getElementById('totalRevenue');
                        const revenue = stats.total_revenue || 0;
                        revenueElement.textContent = new Intl.NumberFormat('fr-FR', {
                            style: 'currency',
                            currency: 'EUR'
                        }).format(revenue);
                        
                    } else {
                        addLog('Erreur lors du chargement des statistiques: ' + data.message);
                    }
                })
                .catch(error => {
                    addLog('Erreur de connexion: ' + error.message);
                });
        }

        // Tester l'API
        function testAPI() {
            addLog('Test de l\'API en cours...');
            
            const tests = [
                { action: 'get_stats', name: 'Statistiques' },
                { action: 'get_users', name: 'Utilisateurs' },
                { action: 'get_products', name: 'Produits' }
            ];
            
            let completedTests = 0;
            
            tests.forEach(test => {
                fetch(`admin_api.php?action=${test.action}`)
                    .then(response => response.json())
                    .then(data => {
                        completedTests++;
                        if (data.success) {
                            addLog(`✅ ${test.name}: OK`);
                        } else {
                            addLog(`❌ ${test.name}: ${data.message}`);
                        }
                        
                        if (completedTests === tests.length) {
                            addLog('Tests API terminés');
                            showNotification('Tests API terminés', 'success');
                        }
                    })
                    .catch(error => {
                        completedTests++;
                        addLog(`❌ ${test.name}: Erreur de connexion`);
                        
                        if (completedTests === tests.length) {
                            addLog('Tests API terminés');
                            showNotification('Tests API terminés', 'error');
                        }
                    });
            });
        }

        // Actualiser les données
        function refreshData() {
            addLog('Actualisation des données...');
            loadDynamicData();
            showNotification('Données actualisées', 'success');
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            addLog('Interface de démonstration chargée');
            loadDynamicData();
            
            // Actualisation automatique toutes les 30 secondes
            setInterval(() => {
                addLog('Actualisation automatique...');
                loadDynamicData();
            }, 30000);
        });

        // Gestion du sidebar
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    </script>

    <style>
        .logs-container {
            max-height: 300px;
            overflow-y: auto;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }

        .log-entry {
            display: flex;
            gap: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .log-entry:last-child {
            border-bottom: none;
        }

        .log-time {
            color: #6c757d;
            font-weight: bold;
            min-width: 80px;
        }

        .log-message {
            color: #495057;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: var(--admin-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--admin-dark);
        }

        .stat-label {
            color: var(--admin-gray);
            font-size: 0.9rem;
        }
    </style>
</body>
</html>
