/* ========================================
   ADMINISTRATION MODERNE - JAVASCRIPT
   ======================================== */

// Variables globales
let charts = {};
let currentSection = 'dashboard';

// ========================================
// INITIALISATION
// ========================================

function initializeDashboard() {
    setupSidebar();
    setupNavigation();
    setupResponsive();
    setupNotifications();
}

// ========================================
// SIDEBAR
// ========================================

function setupSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    // Toggle sidebar sur mobile
    window.toggleSidebar = function() {
        sidebar.classList.toggle('show');
    };
    
    // Fermer sidebar sur mobile quand on clique à l'extérieur
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(e.target) && !e.target.closest('.btn-toggle-sidebar')) {
                sidebar.classList.remove('show');
            }
        }
    });
}

// ========================================
// NAVIGATION
// ========================================

function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    
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
    
    // Mettre à jour le titre de la page
    const pageTitle = document.querySelector('.page-title');
    const titles = {
        'dashboard': 'Tableau de bord',
        'products': 'Gestion des produits',
        'orders': 'Commandes',
        'customers': 'Clients',
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
        case 'analytics':
            loadAnalyticsContent();
            break;
        case 'settings':
            loadSettingsContent();
            break;
    }
}

// ========================================
// STATISTIQUES
// ========================================

async function loadStats() {
    try {
        const response = await fetch('admin_api.php?action=get_stats');
        const data = await response.json();
        
        if (data.success) {
            updateStatsCards(data.stats);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des statistiques:', error);
        showError('Erreur lors du chargement des statistiques');
    }
}

function updateStatsCards(stats) {
    const elements = {
        'totalProducts': stats.total_products || 0,
        'totalOrders': stats.total_orders || 0,
        'totalCustomers': stats.total_customers || 0,
        'totalRevenue': formatCurrency(stats.total_revenue || 0)
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            animateNumber(element, value);
        }
    });
}

function animateNumber(element, targetValue) {
    const startValue = parseInt(element.textContent) || 0;
    const duration = 1000;
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
        element.textContent = currentValue;
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    }
    
    requestAnimationFrame(updateNumber);
}

// ========================================
// GRAPHIQUES
// ========================================

async function loadCharts() {
    await loadSalesChart();
    await loadCategoryChart();
}

async function loadSalesChart() {
    try {
        const response = await fetch('admin_api.php?action=get_sales_data');
        const data = await response.json();
        
        if (data.success) {
            createSalesChart(data.sales_data);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des données de vente:', error);
    }
}

function createSalesChart(salesData) {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    
    charts.sales = new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.labels || [],
            datasets: [{
                label: 'Ventes',
                data: salesData.values || [],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        color: '#e2e8f0'
                    }
                }
            }
        }
    });
}

async function loadCategoryChart() {
    try {
        const response = await fetch('admin_api.php?action=get_category_data');
        const data = await response.json();
        
        if (data.success) {
            createCategoryChart(data.category_data);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des données de catégorie:', error);
    }
}

function createCategoryChart(categoryData) {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    const colors = [
        '#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
        '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
    ];
    
    charts.category = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categoryData.labels || [],
            datasets: [{
                data: categoryData.values || [],
                backgroundColor: colors.slice(0, categoryData.labels?.length || 0),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

// ========================================
// ACTIVITÉ RÉCENTE
// ========================================

async function loadRecentActivity() {
    try {
        const response = await fetch('admin_api.php?action=get_recent_activity');
        const data = await response.json();
        
        if (data.success) {
            displayRecentActivity(data.activities);
        }
    } catch (error) {
        console.error('Erreur lors du chargement de l\'activité récente:', error);
    }
}

function displayRecentActivity(activities) {
    const container = document.getElementById('activityList');
    if (!container) return;
    
    container.innerHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon">
                <i class="bi bi-${getActivityIcon(activity.type)}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">${activity.title}</div>
                <div class="activity-time">${formatTime(activity.created_at)}</div>
            </div>
        </div>
    `).join('');
}

function getActivityIcon(type) {
    const icons = {
        'order': 'cart-check',
        'user': 'person-plus',
        'product': 'box-seam',
        'payment': 'credit-card',
        'login': 'box-arrow-in-right'
    };
    return icons[type] || 'circle';
}

// ========================================
// COMMANDES RÉCENTES
// ========================================

async function loadRecentOrders() {
    try {
        const response = await fetch('admin_api.php?action=get_recent_orders');
        const data = await response.json();
        
        if (data.success) {
            displayRecentOrders(data.orders);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des commandes récentes:', error);
    }
}

function displayRecentOrders(orders) {
    const container = document.getElementById('recentOrders');
    if (!container) return;
    
    container.innerHTML = orders.map(order => `
        <div class="order-item">
            <div class="order-icon">
                <i class="bi bi-cart-check"></i>
            </div>
            <div class="order-content">
                <div class="order-title">Commande #${order.id}</div>
                <div class="order-time">${formatTime(order.created_at)}</div>
            </div>
            <div class="order-status ${order.status}">${order.status}</div>
        </div>
    `).join('');
}

// ========================================
// SECTIONS SPÉCIALISÉES
// ========================================

function loadDashboardContent() {
    // Le contenu du dashboard est déjà chargé
    loadStats();
    loadCharts();
    loadRecentActivity();
    loadRecentOrders();
}

function loadProductsContent() {
    const content = document.querySelector('.admin-content');
    content.innerHTML = `
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestion des produits</h2>
                    <button class="btn btn-primary" onclick="showAddProductModal()">
                        <i class="bi bi-plus"></i> Ajouter un produit
                    </button>
                </div>
                <div class="card">
                    <div class="card-body">
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
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="loading">Chargement des produits...</div>
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
    
    loadProductsTable();
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
                            <table class="table table-hover" id="ordersTable">
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
                                            <div class="loading">Chargement des commandes...</div>
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
    
    loadOrdersTable();
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
                            <table class="table table-hover" id="customersTable">
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
                                            <div class="loading">Chargement des clients...</div>
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
    
    loadCustomersTable();
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

// ========================================
// RESPONSIVE
// ========================================

function setupResponsive() {
    window.addEventListener('resize', function() {
        // Redimensionner les graphiques
        Object.values(charts).forEach(chart => {
            if (chart && chart.resize) {
                chart.resize();
            }
        });
    });
}

// ========================================
// NOTIFICATIONS
// ========================================

function setupNotifications() {
    // Simuler des notifications
    window.showNotifications = function() {
        // Implémenter le système de notifications
        console.log('Affichage des notifications');
    };
}

// ========================================
// UTILITAIRES
// ========================================

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
    
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 60) {
        return `Il y a ${minutes} min`;
    } else if (hours < 24) {
        return `Il y a ${hours}h`;
    } else {
        return `Il y a ${days} jour${days > 1 ? 's' : ''}`;
    }
}

function showError(message) {
    // Implémenter l'affichage d'erreurs
    console.error(message);
}

function showSuccess(message) {
    // Implémenter l'affichage de succès
    console.log(message);
}

// ========================================
// FONCTIONS GLOBALES
// ========================================

window.toggleSidebar = toggleSidebar;
window.showNotifications = showNotifications;
window.showAddProductModal = function() {
    console.log('Affichage du modal d\'ajout de produit');
};

// ========================================
// CHARGEMENT DES DONNÉES
// ========================================

async function loadProductsTable() {
    try {
        const response = await fetch('admin_api.php?action=get_products');
        const data = await response.json();
        
        if (data.success) {
            displayProductsTable(data.products);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des produits:', error);
    }
}

function displayProductsTable(products) {
    const tbody = document.querySelector('#productsTable tbody');
    if (!tbody) return;
    
    tbody.innerHTML = products.map(product => `
        <tr>
            <td>
                <img src="${product.image || 'https://via.placeholder.com/50x50'}" 
                     alt="${product.name}" 
                     class="rounded" 
                     width="50" 
                     height="50">
            </td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>${formatCurrency(product.price)}</td>
            <td>${product.stock || 0}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editProduct(${product.id})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${product.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

async function loadOrdersTable() {
    try {
        const response = await fetch('admin_api.php?action=get_orders');
        const data = await response.json();
        
        if (data.success) {
            displayOrdersTable(data.orders);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des commandes:', error);
    }
}

function displayOrdersTable(orders) {
    const tbody = document.querySelector('#ordersTable tbody');
    if (!tbody) return;
    
    tbody.innerHTML = orders.map(order => `
        <tr>
            <td>#${order.id}</td>
            <td>${order.customer_name}</td>
            <td>${formatCurrency(order.total)}</td>
            <td><span class="badge bg-${getStatusColor(order.status)}">${order.status}</span></td>
            <td>${formatTime(order.created_at)}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(${order.id})">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-success" onclick="updateOrderStatus(${order.id})">
                    <i class="bi bi-check"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

async function loadCustomersTable() {
    try {
        const response = await fetch('admin_api.php?action=get_users');
        const data = await response.json();
        
        if (data.success) {
            displayCustomersTable(data.users);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des clients:', error);
    }
}

function displayCustomersTable(customers) {
    const tbody = document.querySelector('#customersTable tbody');
    if (!tbody) return;
    
    tbody.innerHTML = customers.map(customer => `
        <tr>
            <td>${customer.username}</td>
            <td>${customer.email}</td>
            <td>${customer.phone || '-'}</td>
            <td>${customer.order_count || 0}</td>
            <td>${formatTime(customer.created_at)}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewCustomer(${customer.id})">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning" onclick="editCustomer(${customer.id})">
                    <i class="bi bi-pencil"></i>
                </button>
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

// Fonctions globales pour les actions
window.editProduct = function(id) { console.log('Édition produit:', id); };
window.deleteProduct = function(id) { console.log('Suppression produit:', id); };
window.viewOrder = function(id) { console.log('Voir commande:', id); };
window.updateOrderStatus = function(id) { console.log('Mettre à jour statut:', id); };
window.viewCustomer = function(id) { console.log('Voir client:', id); };
window.editCustomer = function(id) { console.log('Édition client:', id); };
