// Gestionnaire des images multiples pour les produits (3 max)
class ProductImageManager {
    constructor() {
        this.maxImages = 3;
        this.currentImages = [];
        this.uploadedFiles = [];
        this.init();
    }

    init() {
        this.setupImageUploadContainer();
        this.bindEvents();
    }

    // Initialiser les champs d'upload
    setupImageUploadContainer() {
        const container = document.getElementById('imageUploadContainer');
        if (!container) return;

        container.innerHTML = '';
        
        for (let i = 0; i < this.maxImages; i++) {
            const uploadSlot = this.createUploadSlot(i);
            container.appendChild(uploadSlot);
        }
    }

    // Créer un slot d'upload
    createUploadSlot(index) {
        const colDiv = document.createElement('div');
        colDiv.className = 'col-md-4 mb-3';
        colDiv.id = `uploadSlot${index}`;

        colDiv.innerHTML = `
            <div class="card border-dashed" style="border: 2px dashed #dee2e6;">
                <div class="card-body text-center p-3">
                    <div class="upload-preview" id="preview${index}" style="display: none;">
                        <img src="" alt="Preview" class="img-fluid mb-2" style="max-height: 100px;">
                        <button type="button" class="btn btn-sm btn-outline-danger d-block mx-auto" onclick="productImageManager.removePreview(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="upload-area" id="uploadArea${index}">
                        <i class="bi bi-cloud-upload display-6 text-muted"></i>
                        <p class="text-muted mb-2">Photo ${index + 1}</p>
                        <input type="file" class="form-control form-control-sm" 
                               id="productImage${index}" 
                               name="images[]" 
                               accept="image/*" 
                               onchange="productImageManager.handleFileSelect(${index}, this)">
                    </div>
                </div>
            </div>
        `;

        return colDiv;
    }

    // Gérer la sélection de fichier
    handleFileSelect(index, input) {
        const file = input.files[0];
        if (!file) return;

        // Validation
        if (!this.validateFile(file)) {
            input.value = '';
            return;
        }

        // Prévisualiser l'image
        this.previewImage(index, file);
        
        // Stocker le fichier
        this.uploadedFiles[index] = file;
    }

    // Valider le fichier
    validateFile(file) {
        // Vérifier le type
        if (!file.type.match(/image\/(jpeg|jpg|png|gif)/)) {
            alert('Format non supporté. Utilisez JPG, PNG ou GIF.');
            return false;
        }

        // Vérifier la taille (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Fichier trop volumineux. Maximum 2MB.');
            return false;
        }

        return true;
    }

    // Prévisualiser l'image
    previewImage(index, file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById(`preview${index}`);
            const uploadArea = document.getElementById(`uploadArea${index}`);
            const img = preview.querySelector('img');

            img.src = e.target.result;
            preview.style.display = 'block';
            uploadArea.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // Supprimer la prévisualisation
    removePreview(index) {
        const preview = document.getElementById(`preview${index}`);
        const uploadArea = document.getElementById(`uploadArea${index}`);
        const input = document.getElementById(`productImage${index}`);

        preview.style.display = 'none';
        uploadArea.style.display = 'block';
        input.value = '';
        this.uploadedFiles[index] = null;
    }

    // Charger les images existantes d'un produit
    loadExistingImages(productId) {
        if (!productId) {
            this.currentImages = [];
            this.hideCurrentImages();
            return;
        }

        fetch(`admin_api.php?action=get_product_images&product_id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.currentImages = data.images || [];
                    this.displayCurrentImages();
                } else {
                    console.error('Erreur lors du chargement des images:', data.message);
                    this.currentImages = [];
                    this.hideCurrentImages();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.currentImages = [];
                this.hideCurrentImages();
            });
    }

    // Afficher les images actuelles
    displayCurrentImages() {
        const container = document.getElementById('currentImagesContainer');
        const imagesList = document.getElementById('currentImagesList');

        if (this.currentImages.length === 0) {
            container.style.display = 'none';
            return;
        }

        imagesList.innerHTML = '';
        
        this.currentImages.forEach((image, index) => {
            const colDiv = document.createElement('div');
            colDiv.className = 'col-md-4 mb-2';
            
            colDiv.innerHTML = `
                <div class="card">
                    <img src="${image.image_url}" class="card-img-top" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Photo ${image.image_order}</small>
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    onclick="productImageManager.removeExistingImage(${image.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            imagesList.appendChild(colDiv);
        });

        container.style.display = 'block';
    }

    // Masquer les images actuelles
    hideCurrentImages() {
        const container = document.getElementById('currentImagesContainer');
        container.style.display = 'none';
    }

    // Supprimer une image existante
    removeExistingImage(imageId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
            return;
        }

        fetch('admin_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=remove_product_image&image_id=${imageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger les images
                const productId = document.getElementById('productId').value;
                this.loadExistingImages(productId);
                
                // Afficher un message de succès
                if (window.adminDashboard) {
                    adminDashboard.showToast('success', 'Image supprimée avec succès');
                }
            } else {
                alert('Erreur lors de la suppression: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'image');
        });
    }

    // Réinitialiser le gestionnaire
    reset() {
        this.currentImages = [];
        this.uploadedFiles = [];
        this.hideCurrentImages();
        this.setupImageUploadContainer();
    }

    // Obtenir les fichiers à uploader
    getFilesToUpload() {
        return this.uploadedFiles.filter(file => file !== null && file !== undefined);
    }

    // Vérifier si on peut ajouter plus d'images
    canAddMoreImages() {
        const existingCount = this.currentImages.length;
        const newCount = this.getFilesToUpload().length;
        return (existingCount + newCount) < this.maxImages;
    }

    // Obtenir le nombre d'images total
    getTotalImageCount() {
        return this.currentImages.length + this.getFilesToUpload().length;
    }

    // Événements
    bindEvents() {
        // Réinitialiser quand on ouvre le modal pour un nouveau produit
        const modal = document.getElementById('addProductModal');
        if (modal) {
            modal.addEventListener('show.bs.modal', (e) => {
                // Si c'est un nouveau produit (pas d'ID), réinitialiser
                setTimeout(() => {
                    const productId = document.getElementById('productId').value;
                    if (!productId) {
                        this.reset();
                    } else {
                        this.loadExistingImages(productId);
                    }
                }, 100);
            });

            modal.addEventListener('hidden.bs.modal', () => {
                this.reset();
            });
        }
    }
}

// Initialiser le gestionnaire d'images
let productImageManager;
document.addEventListener('DOMContentLoaded', function() {
    productImageManager = new ProductImageManager();
});
