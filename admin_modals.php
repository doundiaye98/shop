<!-- Modal Ajouter/Modifier Utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Ajouter un utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <input type="hidden" id="userId" name="user_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="firstName" name="first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="lastName" name="last_name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Laissez vide pour conserver le mot de passe actuel lors de la modification</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Rôle *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user">Utilisateur</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                            <label class="form-check-label" for="isActive">
                                Compte actif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier Produit -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="bi bi-box-seam me-2"></i>Ajouter un produit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="productId" name="product_id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Nom du produit *</label>
                                <input type="text" class="form-control" id="productName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Catégorie *</label>
                                <select class="form-select" id="productCategory" name="category" required>
                                    <option value="">Choisir une catégorie</option>
                                    <option value="bebe">Bébé</option>
                                    <option value="fille">Fille</option>
                                    <option value="garcon">Garçon</option>
            


                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Prix *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="productStock" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="productStock" name="stock" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="productPromoPrice" class="form-label">Prix promo</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="productPromoPrice" name="promo_price" step="0.01" min="0">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Photos du produit (3 max) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-images me-2"></i>Photos du produit 
                            <span class="badge bg-info ms-2">3 max</span>
                        </label>
                        
                        <!-- Photos actuelles -->
                        <div id="currentImagesContainer" class="mb-3" style="display: none;">
                            <div class="row" id="currentImagesList"></div>
                        </div>
                        
                        <!-- Upload de nouvelles photos -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row" id="imageUploadContainer">
                                    <!-- Les champs d'upload seront générés dynamiquement -->
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Formats acceptés : JPG, PNG, GIF. Taille max : 2MB par image. Maximum 3 photos par produit.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmation Suppression -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet élément ?</p>
                <p class="text-muted mb-0">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash me-2"></i>Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Utilisateur -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">
                    <i class="bi bi-person-circle me-2"></i>Détails de l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations personnelles</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom d'utilisateur :</strong></td>
                                <td id="detailUsername">-</td>
                            </tr>
                            <tr>
                                <td><strong>Email :</strong></td>
                                <td id="detailEmail">-</td>
                            </tr>
                            <tr>
                                <td><strong>Prénom :</strong></td>
                                <td id="detailFirstName">-</td>
                            </tr>
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td id="detailLastName">-</td>
                            </tr>
                            <tr>
                                <td><strong>Rôle :</strong></td>
                                <td id="detailRole">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Statistiques d'activité</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td id="detailStatus">-</td>
                            </tr>
                            <tr>
                                <td><strong>Dernière connexion :</strong></td>
                                <td id="detailLastLogin">-</td>
                            </tr>
                            <tr>
                                <td><strong>Date d'inscription :</strong></td>
                                <td id="detailCreatedAt">-</td>
                            </tr>
                            <tr>
                                <td><strong>Total des sessions :</strong></td>
                                <td id="detailTotalSessions">-</td>
                            </tr>
                            <tr>
                                <td><strong>Temps total en ligne :</strong></td>
                                <td id="detailTotalTime">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Historique des connexions récentes</h6>
                    <div id="userSessionsHistory">
                        <div class="text-center text-muted">
                            <i class="bi bi-arrow-clockwise"></i>
                            <p>Chargement de l'historique...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" id="editUserFromDetails">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="successToastBody">
            Opération réussie !
        </div>
    </div>
    
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong class="me-auto">Erreur</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastBody">
            Une erreur s'est produite !
        </div>
    </div>
</div>
