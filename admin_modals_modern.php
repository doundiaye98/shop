<!-- ========================================
     MODALS D'ADMINISTRATION MODERNE
     ======================================== -->

<!-- Modal Ajouter/Modifier Produit -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Ajouter un produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Nom du produit *</label>
                                <input type="text" class="form-control" id="productName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Prix (€) *</label>
                                <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
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

<!-- Modal Supprimer Produit -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce produit ?</p>
                <p class="text-muted">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteProduct()">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Commande -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Détails de la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetails">
                    <!-- Détails chargés dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="updateOrderStatus()">Mettre à jour le statut</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mettre à jour Statut Commande -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Mettre à jour le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="statusOrderId" name="id">
                    
                    <div class="mb-3">
                        <label for="orderStatus" class="form-label">Nouveau statut</label>
                        <select class="form-select" id="orderStatus" name="status" required>
                            <option value="pending">En attente</option>
                            <option value="processing">En cours de traitement</option>
                            <option value="shipped">Expédié</option>
                            <option value="delivered">Livré</option>
                            <option value="cancelled">Annulé</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveOrderStatus()">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Client -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">Détails du client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="customerDetails">
                    <!-- Détails chargés dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="editCustomer()">Modifier</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Client -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Modifier le client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    <input type="hidden" id="customerId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerUsername" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="customerUsername" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customerEmail" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="customerPhone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerRole" class="form-label">Rôle</label>
                                <select class="form-select" id="customerRole" name="role">
                                    <option value="user">Utilisateur</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveCustomer()">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifications -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notificationsList">
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="bi bi-cart-check text-primary"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Nouvelle commande</div>
                            <div class="notification-text">Commande #1234 reçue</div>
                            <div class="notification-time">Il y a 5 minutes</div>
                        </div>
                    </div>
                    
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="bi bi-person-plus text-success"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Nouveau client</div>
                            <div class="notification-text">Marie Dupont s'est inscrite</div>
                            <div class="notification-time">Il y a 1 heure</div>
                        </div>
                    </div>
                    
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Stock faible</div>
                            <div class="notification-text">Robe rose - Stock: 2</div>
                            <div class="notification-time">Il y a 2 heures</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary">Marquer tout comme lu</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="successToastBody">
            <!-- Message de succès -->
        </div>
    </div>
    
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
            <strong class="me-auto">Erreur</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastBody">
            <!-- Message d'erreur -->
        </div>
    </div>
</div>

<style>
/* Styles pour les modals */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-header {
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
}

/* Styles pour les notifications */
.notification-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    background: #f3f4f6;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.notification-text {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.notification-time {
    color: #9ca3af;
    font-size: 0.75rem;
}

/* Styles pour les toasts */
.toast {
    border-radius: 8px;
    border: none;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.toast-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-xl {
        max-width: calc(100% - 1rem);
    }
    
    .notification-item {
        padding: 0.75rem;
    }
    
    .notification-icon {
        width: 35px;
        height: 35px;
        margin-right: 0.75rem;
    }
}
</style>
