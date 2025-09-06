// Script principal pour le tableau de bord d'administration
class AdminDashboard {
    constructor() {
        this.currentDeleteId = null;
        this.currentDeleteType = null;
        this.charts = {};
        this.init();
    }

    init() {
        this.loadDashboardStats();
        this.loadUsers();
        this.loadProducts();
        this.initCharts();
        this.bindEvents();
        this.startAutoRefresh();
    }

    // Charger les statistiques du tableau de bord
    async loadDashboardStats() {
        try {
            const response = await fetch('admin_api.php?action=get_stats');
            const data = await response.json();
            
            if (data.success) {
                this.updateStatsCards(data.stats);
                this.updateCharts(data);
                this.loadOnlineUsers();
            }
        } catch (error) {
            console.error('Erreur lors du chargement des statistiques:', error);
        }
    }

    // Mettre à jour les cartes de statistiques
    updateStatsCards(stats) {
        document.getElementById('total-users').textContent = stats.total_users || 0;
        document.getElementById('total-products').textContent = stats.total_products || 0;
        document.getElementById('total-orders').textContent = stats.total_orders || 0;
        document.getElementById('total-revenue').textContent = (stats.total_revenue || 0) + ' €';

        // Mettre à jour les pourcentages de changement
        this.updateChangeIndicator('users-change', stats.users_change);
        this.updateChangeIndicator('products-change', stats.products_change);
        this.updateChangeIndicator('orders-change', stats.orders_change);
        this.updateChangeIndicator('revenue-change', stats.revenue_change);
    }

    // Mettre à jour les indicateurs de changement
    updateChangeIndicator(elementId, change) {
        const element = document.getElementById(elementId);
        if (element && change !== undefined) {
            const isPositive = change >= 0;
            element.textContent = (isPositive ? '+' : '') + change + '%';
            element.className = `stats-change ${isPositive ? 'positive' : 'negative'}`;
        }
    }

    // Initialiser les graphiques
    initCharts() {
        // Graphique des ventes mensuelles
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            this.charts.sales = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Ventes mensuelles (€)',
                        data: [],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
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
                            ticks: {
                                callback: function(value) {
                                    return value + ' €';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Graphique des catégories
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            this.charts.category = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    // Mettre à jour les graphiques
    updateCharts(data) {
        if (data.sales_data && this.charts.sales) {
            this.charts.sales.data.labels = data.sales_data.labels;
            this.charts.sales.data.datasets[0].data = data.sales_data.values;
            this.charts.sales.update();
        }

        if (data.category_data && this.charts.category) {
            this.charts.category.data.labels = data.category_data.labels;
            this.charts.category.data.datasets[0].data = data.category_data.values;
            this.charts.category.update();
        }
    }

    // Charger les utilisateurs
    async loadUsers() {
        try {
            const response = await fetch('admin_api.php?action=get_users');
            const data = await response.json();
            
            if (data.success) {
                this.renderUsersTable(data.users);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des utilisateurs:', error);
        }
    }

    // Afficher le tableau des utilisateurs
    renderUsersTable(users) {
        const tbody = document.getElementById('usersTableBody');
        if (!tbody) return;

        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé</td></tr>';
            return;
        }

        tbody.innerHTML = users.map(user => `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <i class="bi bi-person-circle fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">${user.username}</div>
                            <small class="text-muted">ID: ${user.id}</small>
                        </div>
                    </div>
                </td>
                <td>${user.email}</td>
                <td>
                    <span class="badge ${user.role === 'admin' ? 'bg-danger' : 'bg-primary'}">
                        ${user.role}
                    </span>
                </td>
                <td>
                    <span class="status-badge ${user.is_online ? 'status-online' : 'status-offline'}">
                        ${user.is_online ? 'En ligne' : 'Hors ligne'}
                    </span>
                </td>
                <td>${user.last_login ? this.formatDate(user.last_login) : 'Jamais'}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="adminDashboard.viewUser(${user.id})" title="Voir les détails">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="adminDashboard.editUser(${user.id})" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="adminDashboard.deleteUser(${user.id})" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Charger les produits
    async loadProducts() {
        try {
            const response = await fetch('admin_api.php?action=get_products');
            const data = await response.json();
            
            if (data.success) {
                this.renderProductsTable(data.products);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des produits:', error);
        }
    }

    // Afficher le tableau des produits
    renderProductsTable(products) {
        const tbody = document.getElementById('productsTableBody');
        if (!tbody) return;

        if (products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun produit trouvé</td></tr>';
            return;
        }

        tbody.innerHTML = products.map(product => `
            <tr>
                <td>
                    <img src="${product.image_url || 'https://via.placeholder.com/50x50?text=No+Image'}" 
                         alt="${product.name}" class="img-thumbnail" style="width: 50px; height: 50px;">
                </td>
                <td>
                    <div class="fw-bold">${product.name}</div>
                    <small class="text-muted">${product.description ? product.description.substring(0, 50) + '...' : 'Aucune description'}</small>
                </td>
                <td>
                    <span class="badge bg-secondary">${product.category}</span>
                </td>
                <td>
                    <div class="fw-bold">${product.price} €</div>
                    ${product.promo_price ? `<small class="text-danger">Promo: ${product.promo_price} €</small>` : ''}
                </td>
                <td>
                    <span class="badge ${this.getStockBadgeClass(product.stock)}">
                        ${product.stock}
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="adminDashboard.editProduct(${product.id})" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="adminDashboard.deleteProduct(${product.id})" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Obtenir la classe CSS pour le badge de stock
    getStockBadgeClass(stock) {
        if (stock === 0) return 'bg-danger';
        if (stock <= 10) return 'bg-warning';
        return 'bg-success';
    }

    // Charger les utilisateurs en ligne
    async loadOnlineUsers() {
        try {
            const response = await fetch('admin_api.php?action=get_online_users');
            const data = await response.json();
            
            if (data.success) {
                this.renderOnlineUsers(data.users);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des utilisateurs en ligne:', error);
        }
    }

    // Afficher les utilisateurs en ligne
    renderOnlineUsers(users) {
        const container = document.getElementById('online-users-list');
        if (!container) return;

        if (users.length === 0) {
            container.innerHTML = '<div class="text-center text-muted"><p>Aucun utilisateur en ligne</p></div>';
            return;
        }

        container.innerHTML = `
            <div class="row">
                ${users.map(user => `
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <i class="bi bi-person-circle fs-4 text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">${user.username}</div>
                                        <small class="text-muted">Connecté depuis ${this.formatDuration(user.session_duration)}</small>
                                    </div>
                                    <div class="text-success">
                                        <i class="bi bi-circle-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Formater une date
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Formater une durée
    formatDuration(minutes) {
        if (minutes < 60) return `${minutes} min`;
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours}h ${mins}min`;
    }

    // Événements
    bindEvents() {
        // Formulaire utilisateur
        document.getElementById('userForm')?.addEventListener('submit', (e) => this.handleUserSubmit(e));
        
        // Formulaire produit
        document.getElementById('productForm')?.addEventListener('submit', (e) => this.handleProductSubmit(e));
        
        // Confirmation de suppression
        document.getElementById('confirmDelete')?.addEventListener('click', () => this.confirmDelete());
        
        // Recherche et filtres
        document.getElementById('userSearch')?.addEventListener('input', (e) => this.filterUsers(e.target.value));
        document.getElementById('productSearch')?.addEventListener('input', (e) => this.filterProducts(e.target.value));
        
        // Filtres de statut
        document.getElementById('userStatusFilter')?.addEventListener('change', (e) => this.filterUsersByStatus(e.target.value));
        document.getElementById('userRoleFilter')?.addEventListener('change', (e) => this.filterUsersByRole(e.target.value));
        
        // Filtres de produits
        document.getElementById('productCategoryFilter')?.addEventListener('change', (e) => this.filterProductsByCategory(e.target.value));
        document.getElementById('productStockFilter')?.addEventListener('change', (e) => this.filterProductsByStock(e.target.value));
    }

    // Gérer la soumission du formulaire utilisateur
    async handleUserSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const userId = formData.get('user_id');
        
        try {
            const response = await fetch('admin_api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showToast('success', data.message || 'Utilisateur enregistré avec succès !');
                this.loadUsers();
                bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                e.target.reset();
            } else {
                this.showToast('error', data.message || 'Erreur lors de l\'enregistrement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('error', 'Erreur de connexion');
        }
    }

    // Gérer la soumission du formulaire produit
    async handleProductSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const productId = formData.get('product_id');
        
        try {
            const response = await fetch('admin_api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showToast('success', data.message || 'Produit enregistré avec succès !');
                this.loadProducts();
                bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
                e.target.reset();
            } else {
                this.showToast('error', data.message || 'Erreur lors de l\'enregistrement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('error', 'Erreur de connexion');
        }
    }

    // Afficher un toast
    showToast(type, message) {
        const toastId = type === 'success' ? 'successToast' : 'errorToast';
        const toastBodyId = type === 'success' ? 'successToastBody' : 'errorToastBody';
        
        document.getElementById(toastBodyId).textContent = message;
        const toast = new bootstrap.Toast(document.getElementById(toastId));
        toast.show();
    }

    // Éditer un utilisateur
    async editUser(userId) {
        try {
            const response = await fetch(`admin_api.php?action=get_user&id=${userId}`);
            const data = await response.json();
            
            if (data.success) {
                const user = data.user;
                document.getElementById('userId').value = user.id;
                document.getElementById('username').value = user.username;
                document.getElementById('email').value = user.email;
                document.getElementById('firstName').value = user.first_name || '';
                document.getElementById('lastName').value = user.last_name || '';
                document.getElementById('role').value = user.role;
                document.getElementById('isActive').checked = user.is_active == 1;
                document.getElementById('password').required = false;
                
                document.getElementById('addUserModalLabel').innerHTML = '<i class="bi bi-pencil me-2"></i>Modifier l\'utilisateur';
                
                const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
                modal.show();
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('error', 'Erreur lors du chargement de l\'utilisateur');
        }
    }

    // Éditer un produit
    async editProduct(productId) {
        try {
            const response = await fetch(`admin_api.php?action=get_product&id=${productId}`);
            const data = await response.json();
            
            if (data.success) {
                const product = data.product;
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productDescription').value = product.description || '';
                document.getElementById('productCategory').value = product.category;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productStock').value = product.stock;
                document.getElementById('productPromoPrice').value = product.promo_price || '';
                
                if (product.image_url) {
                    document.getElementById('currentImage').src = product.image_url;
                    document.getElementById('currentImageContainer').style.display = 'block';
                } else {
                    document.getElementById('currentImageContainer').style.display = 'none';
                }
                
                document.getElementById('addProductModalLabel').innerHTML = '<i class="bi bi-pencil me-2"></i>Modifier le produit';
                
                const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
                modal.show();
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('error', 'Erreur lors du chargement du produit');
        }
    }

    // Supprimer un utilisateur
    deleteUser(userId) {
        this.currentDeleteId = userId;
        this.currentDeleteType = 'user';
        this.showDeleteModal();
    }

    // Supprimer un produit
    deleteProduct(productId) {
        this.currentDeleteId = productId;
        this.currentDeleteType = 'product';
        this.showDeleteModal();
    }

    // Afficher le modal de confirmation de suppression
    showDeleteModal() {
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Confirmer la suppression
    async confirmDelete() {
        if (!this.currentDeleteId || !this.currentDeleteType) return;
        
        try {
            const response = await fetch('admin_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete_${this.currentDeleteType}&id=${this.currentDeleteId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showToast('success', data.message || 'Élément supprimé avec succès !');
                
                if (this.currentDeleteType === 'user') {
                    this.loadUsers();
                } else {
                    this.loadProducts();
                }
                
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            } else {
                this.showToast('error', data.message || 'Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('error', 'Erreur de connexion');
        }
        
        this.currentDeleteId = null;
        this.currentDeleteType = null;
    }

    // Voir les détails d'un utilisateur
    async viewUser(userId) {
        try {
            const response = await fetch(`admin_api.php?action=get_user_details&id=${userId}`);
            const data = await response.json();
            
            if (data.success) {
                const user = data.user;
                document.getElementById('detailUsername').textContent = user.username;
                document.getElementById('detailEmail').textContent = user.email;
                document.getElementById('detailFirstName').textContent = user.first_name || '-';
                document.getElementById('detailLastName').textContent = user.last_name || '-';
                document.getElementById('detailRole').textContent = user.role;
                document.getElementById('detailStatus').innerHTML = `<span class="status-badge ${user.is_online ? 'status-online' : 'status-offline'}">${user.is_online ? 'En ligne' : 'Hors ligne'}</span>`;
                document.getElementById('detailLastLogin').textContent = user.last_login ? this.formatDate(user.last_login) : 'Jamais';
                document.getElementById('detailCreatedAt').textContent = this.formatDate(user.created_at);
                document.getElementById('detailTotalSessions').textContent = user.total_sessions || 0;
                document.getElementById('detailTotalTime').textContent = this.formatDuration(user.total_time || 0);
                
                // Historique des sessions
                if (user.sessions && user.sessions.length > 0) {
                    document.getElementById('userSessionsHistory').innerHTML = user.sessions.map(session => `
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <div>
                                <small class="text-muted">${this.formatDate(session.start_time)}</small>
                                <div>Durée: ${this.formatDuration(session.duration || 0)}</div>
                            </div>
                            <span class="badge bg-secondary">${session.ip_address || 'N/A'}</span>
                        </div>
                    `).join('');
                } else {
                    document.getElementById('userSessionsHistory').innerHTML = '<div class="text-center text-muted"><p>Aucune session enregistrée</p></div>';
                }
                
                const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                modal.show();
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showToast('error', 'Erreur lors du chargement des détails');
        }
    }

    // Filtres
    filterUsers(searchTerm) {
        // Implémentation du filtrage côté client
        const rows = document.querySelectorAll('#usersTableBody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm.toLowerCase()) ? '' : 'none';
        });
    }

    filterProducts(searchTerm) {
        const rows = document.querySelectorAll('#productsTableBody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm.toLowerCase()) ? '' : 'none';
        });
    }

    filterUsersByStatus(status) {
        // Implémentation du filtrage par statut
    }

    filterUsersByRole(role) {
        // Implémentation du filtrage par rôle
    }

    filterProductsByCategory(category) {
        // Implémentation du filtrage par catégorie
    }

    filterProductsByStock(stock) {
        // Implémentation du filtrage par stock
    }

    // Actualisation automatique
    startAutoRefresh() {
        setInterval(() => {
            this.loadDashboardStats();
        }, 30000); // Actualiser toutes les 30 secondes
    }
}

// Initialiser le tableau de bord quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.adminDashboard = new AdminDashboard();
});

// Réinitialiser les modals lors de leur fermeture
document.addEventListener('DOMContentLoaded', () => {
    // Modal utilisateur
    document.getElementById('addUserModal')?.addEventListener('hidden.bs.modal', () => {
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('password').required = true;
        document.getElementById('addUserModalLabel').innerHTML = '<i class="bi bi-person-plus me-2"></i>Ajouter un utilisateur';
    });
    
    // Modal produit
    document.getElementById('addProductModal')?.addEventListener('hidden.bs.modal', () => {
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('currentImageContainer').style.display = 'none';
        document.getElementById('addProductModalLabel').innerHTML = '<i class="bi bi-box-seam me-2"></i>Ajouter un produit';
    });
});
