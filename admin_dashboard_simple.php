<?php
// Dashboard simplifié pour tester le bouton d'ajout de produit
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: backend/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Simplifié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="admin-modern.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>
        
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-shield-check"></i> <span class="sidebar-title">Admin</span></h3>
                <button class="btn-close-sidebar d-md-none" id="closeSidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-box-seam"></i>
                    <span>Produits</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <button class="btn-toggle-sidebar d-md-none" id="toggleSidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Dashboard Admin Simplifié</h1>
                </div>
                <div class="top-bar-right">
                    <div class="user-menu">
                        <span class="user-greeting d-none d-md-inline">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['username']) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="backend/logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Test du bouton d'ajout de produit -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4><i class="bi bi-box-seam"></i> Test Bouton Ajout Produit</h4>
                                    <button class="btn btn-primary" onclick="addNewProduct()">
                                        <i class="bi bi-plus-circle"></i> Ajouter un produit
                                    </button>
                                </div>
                                <div class="card-body">
                                    <p>Cliquez sur le bouton "Ajouter un produit" pour tester la fonctionnalité.</p>
                                    <div class="mt-3">
                                        <button class="btn btn-outline-primary btn-sm me-2" onclick="showNotification('Test de notification de succès', 'success')">
                                            <i class="bi bi-check-circle"></i> Test Succès
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm me-2" onclick="showNotification('Test de notification d&apos;erreur', 'error')">
                                            <i class="bi bi-exclamation-triangle"></i> Test Erreur
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm me-2" onclick="showNotification('Test de notification d&apos;avertissement', 'warning')">
                                            <i class="bi bi-exclamation-circle"></i> Test Avertissement
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="showNotification('Test de notification d&apos;information', 'info')">
                                            <i class="bi bi-info-circle"></i> Test Info
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-success btn-sm" onclick="alert('Test simple - si vous voyez ceci, JavaScript fonctionne')">
                                            <i class="bi bi-bug"></i> Test JavaScript Simple
                                        </button>
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
                                                    <th class="product-col">Produit</th>
                                                    <th class="category-col d-none d-md-table-cell">Catégorie</th>
                                                    <th class="price-col">Prix</th>
                                                    <th class="stock-col d-none d-lg-table-cell">Stock</th>
                                                    <th class="actions-col">Actions</th>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter/Modifier Produit -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Ajouter un produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" id="productId" name="id">
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Nom du produit *</label>
                                    <input type="text" class="form-control" id="productName" name="name" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="productCategory" class="form-label">Catégorie *</label>
                                    <select class="form-select" id="productCategory" name="category" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="Bébé">Bébé</option>
                                        <option value="Fille">Fille</option>
                                        <option value="Garçon">Garçon</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Prix (€) *</label>
                                    <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="productStock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="productStock" name="stock" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="productImages" class="form-label">Images du produit</label>
                            <input type="file" class="form-control" id="productImages" name="images[]" multiple accept="image/*">
                            <div class="form-text">Sélectionnez une ou plusieurs images (max 3, formats: JPG, PNG, GIF, max 2MB chacune)</div>
                        </div>
                        
                        <div class="mb-3" id="imagePreview" style="display: none;">
                            <label class="form-label">Aperçu des images</label>
                            <div id="previewContainer" class="d-flex flex-wrap gap-2">
                                <!-- Aperçus des images -->
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="productPromoPrice" class="form-label">Prix promotionnel (€)</label>
                            <input type="number" class="form-control" id="productPromoPrice" name="promo_price" step="0.01" min="0">
                            <div class="form-text">Laissez vide si pas de promotion</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonction pour ajouter un nouveau produit
        function addNewProduct() {
            console.log('Bouton ajouter produit cliqué');
            
            // Vérifier si le modal existe
            const modal = document.getElementById('productModal');
            if (!modal) {
                console.error('Modal productModal non trouvé !');
                showResult('Erreur: Modal d\'ajout de produit non trouvé.', 'danger');
                return;
            }
            
            // Ouvrir le modal
            openProductModal();
            showResult('Modal d\'ajout de produit ouvert avec succès !', 'success');
        }
        
        // Ouvrir le modal de produit
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
            
            modal.show();
        }
        
        // Sauvegarder le produit
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
                showResult('Veuillez remplir tous les champs obligatoires', 'danger');
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
                    showResult(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                    loadProductsTable();
                } else {
                    showResult(data.message, 'danger');
                }
            })
            .catch(error => {
                showResult('Erreur lors de la sauvegarde: ' + error.message, 'danger');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }
        
        // Charger les produits
        function loadProductsTable() {
            fetch('admin_api.php?action=get_products')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProductsTable(data.products);
                    } else {
                        showResult('Erreur lors du chargement des produits: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    showResult('Erreur de connexion: ' + error.message, 'danger');
                });
        }
        
        
        // Fonctions utilitaires
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount);
        }
        
        // Système de notifications amélioré
        function showNotification(message, type = 'info', duration = 5000) {
            console.log('showNotification appelée avec:', message, type, duration);
            
            try {
                // Créer l'élément de notification
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                
                // Définir l'icône selon le type
                let icon = 'bi-info-circle';
                switch(type) {
                    case 'success':
                        icon = 'bi-check-circle-fill';
                        break;
                    case 'error':
                    case 'danger':
                        icon = 'bi-exclamation-triangle-fill';
                        break;
                    case 'warning':
                        icon = 'bi-exclamation-circle-fill';
                        break;
                    case 'info':
                    default:
                        icon = 'bi-info-circle-fill';
                        break;
                }
                
                // Contenu de la notification
                notification.innerHTML = `
                    <div class="notification-content">
                        <i class="bi ${icon}"></i>
                        <span>${message}</span>
                        <button class="btn-close-notification" onclick="closeNotification(this)">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                
                // Ajouter au DOM
                document.body.appendChild(notification);
                console.log('Notification ajoutée au DOM');
                
                // Animation d'entrée
                setTimeout(() => {
                    notification.classList.add('show');
                    console.log('Animation d\'entrée déclenchée');
                }, 100);
                
                // Suppression automatique
                if (duration > 0) {
                    setTimeout(() => {
                        const closeBtn = notification.querySelector('.btn-close-notification');
                        if (closeBtn) {
                            closeNotification(closeBtn);
                        }
                    }, duration);
                }
            } catch (error) {
                console.error('Erreur dans showNotification:', error);
                // Fallback vers alert si la notification échoue
                alert('Notification: ' + message);
            }
        }
        
        function closeNotification(button) {
            const notification = button.closest('.notification');
            if (notification) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }
        
        // Alias pour compatibilité
        function showResult(message, type) {
            showNotification(message, type);
        }
        
        // Fonctions CRUD (simplifiées)
        function editProduct(id) {
            showResult('Fonction d\'édition non implémentée dans cette version simplifiée', 'info');
        }
        
        function deleteProduct(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                showResult('Fonction de suppression non implémentée dans cette version simplifiée', 'info');
            }
        }
        
        // Gestion de la sidebar mobile
        function initMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            function showSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            
            function hideSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', showSidebar);
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', hideSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', hideSidebar);
            }
            
            // Fermer la sidebar lors du redimensionnement vers desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    hideSidebar();
                }
            });
        }
        
        // Améliorer l'affichage des produits pour mobile
        function displayProductsTable(products) {
            const tbody = document.querySelector('#productsTable tbody');
            if (!tbody) return;
            
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun produit trouvé</td></tr>';
                return;
            }
            
            tbody.innerHTML = products.map(product => `
                <tr>
                    <td class="product-col">
                        <div class="product-info">
                            <img src="${product.image_url || 'assets/images/no-image.png'}" alt="${product.name}" class="product-thumb">
                            <div class="product-details">
                                <div class="product-name">${product.name}</div>
                                <div class="product-category d-md-none text-muted small">${product.category}</div>
                                <div class="product-stock d-lg-none text-muted small">Stock: ${product.stock}</div>
                            </div>
                        </div>
                    </td>
                    <td class="category-col d-none d-md-table-cell">${product.category}</td>
                    <td class="price-col">
                        <div class="price-info">
                            <div class="price-main">${formatCurrency(product.price)}</div>
                            ${product.promo_price ? `<div class="price-promo text-success small">Promo: ${formatCurrency(product.promo_price)}</div>` : ''}
                        </div>
                    </td>
                    <td class="stock-col d-none d-lg-table-cell">
                        <span class="badge ${product.stock > 0 ? 'badge-success' : 'badge-danger'}">
                            ${product.stock} en stock
                        </span>
                    </td>
                    <td class="actions-col">
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        // Test de la fonction de notification au chargement
        function testNotification() {
            console.log('Test de notification...');
            showNotification('Test de notification au chargement', 'info', 3000);
        }
        
        // Charger les données au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard simplifié chargé');
            initMobileSidebar();
            loadProductsTable();
            
            // Test automatique après 2 secondes
            setTimeout(testNotification, 2000);
        });
    </script>
</body>
</html>
