// Gestionnaire du panier
class CartManager {
    constructor() {
        this.cartItems = [];
        this.total = 0;
        this.itemCount = 0;
        this.init();
    }
    
    // Initialiser le gestionnaire
    init() {
        this.loadCart();
        this.updateCartIcon();
        this.setupEventListeners();
    }
    
    // Charger le contenu du panier depuis le serveur
    async loadCart() {
        try {
            const response = await fetch('backend/cart_api.php', {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const data = await response.json();
                this.cartItems = data.cart_items || [];
                this.total = data.total || 0;
                this.itemCount = data.item_count || 0;
                this.updateCartIcon();
                this.updateCartDisplay();
            } else if (response.status === 401) {
                // Utilisateur non connecté
                this.showLoginMessage();
            }
        } catch (error) {
            console.error('Erreur lors du chargement du panier:', error);
        }
    }
    
    // Ajouter un produit au panier
    async addToCart(productId, quantity = 1) {
        try {
            const response = await fetch('backend/cart_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                this.showNotification(data.message, 'success');
                await this.loadCart(); // Recharger le panier
                return true;
            } else {
                const errorData = await response.json();
                this.showNotification(errorData.error, 'error');
                return false;
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout au panier:', error);
            this.showNotification('Erreur lors de l\'ajout au panier', 'error');
            return false;
        }
    }
    
    // Mettre à jour la quantité d'un produit
    async updateQuantity(productId, quantity) {
        try {
            const response = await fetch('backend/cart_api.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                this.showNotification(data.message, 'success');
                await this.loadCart(); // Recharger le panier
                return true;
            } else {
                const errorData = await response.json();
                this.showNotification(errorData.error, 'error');
                return false;
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour:', error);
            this.showNotification('Erreur lors de la mise à jour', 'error');
            return false;
        }
    }
    
    // Supprimer un produit du panier
    async removeFromCart(productId) {
        try {
            const response = await fetch('backend/cart_api.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    product_id: productId
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                this.showNotification(data.message, 'success');
                await this.loadCart(); // Recharger le panier
                return true;
            } else {
                const errorData = await response.json();
                this.showNotification(errorData.error, 'error');
                return false;
            }
        } catch (error) {
            console.error('Erreur lors de la suppression:', error);
            this.showNotification('Erreur lors de la suppression', 'error');
            return false;
        }
    }
    
    // Mettre à jour l'icône du panier
    updateCartIcon() {
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) {
            const badge = cartIcon.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = this.itemCount;
                badge.style.display = this.itemCount > 0 ? 'block' : 'none';
            }
        }
    }
    
    // Mettre à jour l'affichage du panier
    updateCartDisplay() {
        const cartContainer = document.getElementById('cart-container');
        if (cartContainer) {
            this.renderCart(cartContainer);
        }
    }
    
    // Rendre le contenu du panier
    renderCart(container) {
        if (this.cartItems.length === 0) {
            container.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart-x"></i>
                    <p>Votre panier est vide</p>
                    <a href="products.php" class="btn btn-primary">Continuer les achats</a>
                </div>
            `;
            return;
        }
        
        let html = `
            <div class="cart-header">
                <h3>Votre Panier (${this.itemCount} article${this.itemCount > 1 ? 's' : ''})</h3>
            </div>
            <div class="cart-items">
        `;
        
        this.cartItems.forEach(item => {
            const price = item.promo_price && item.promo_price < item.price ? item.promo_price : item.price;
            const totalPrice = price * item.quantity;
            
            html += `
                <div class="cart-item" data-product-id="${item.product_id}">
                    <div class="item-image">
                        <img src="${item.image || 'https://via.placeholder.com/80x80?text=Produit'}" alt="${item.name}">
                    </div>
                    <div class="item-details">
                        <h4>${item.name}</h4>
                        <p class="item-price">
                            ${item.promo_price && item.promo_price < item.price 
                                ? `<span class="old-price">${item.price} €</span> <span class="promo-price">${item.promo_price} €</span>`
                                : `<span class="current-price">${item.price} €</span>`
                            }
                        </p>
                        <div class="quantity-controls">
                            <button class="btn-quantity" onclick="cartManager.changeQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="btn-quantity" onclick="cartManager.changeQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <p class="item-total">Total: ${totalPrice.toFixed(2)} €</p>
                    </div>
                    <div class="item-actions">
                        <button class="btn-remove" onclick="cartManager.removeFromCart(${item.product_id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        html += `
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <strong>Total: ${this.total.toFixed(2)} €</strong>
                </div>
                <div class="cart-actions">
                    <a href="products.php" class="btn btn-outline-secondary">Continuer les achats</a>
                    <a href="panier.php" class="btn btn-primary">Voir le panier complet</a>
                    <a href="commande.php" class="btn btn-success">Commander</a>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    // Changer la quantité d'un produit
    async changeQuantity(productId, newQuantity) {
        if (newQuantity <= 0) {
            await this.removeFromCart(productId);
        } else {
            await this.updateQuantity(productId, newQuantity);
        }
    }
    
    // Afficher une notification
    showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        
        // Ajouter au DOM
        document.body.appendChild(notification);
        
        // Animation d'apparition
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    // Afficher le message de connexion
    showLoginMessage() {
        this.showNotification('Veuillez vous connecter pour utiliser le panier', 'warning');
    }
    
    // Configurer les écouteurs d'événements
    setupEventListeners() {
        // Écouter les clics sur les boutons "Ajouter au panier"
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-add-cart')) {
                e.preventDefault();
                const productId = e.target.dataset.productId;
                if (productId) {
                    this.addToCart(parseInt(productId), 1);
                }
            }
        });
    }
}

// Initialiser le gestionnaire du panier quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.cartManager = new CartManager();
});

// Fonction globale pour ajouter au panier (utilisée dans product_detail.php)
function addToCart(productId, quantity = 1) {
    if (window.cartManager) {
        return window.cartManager.addToCart(productId, quantity);
    } else {
        console.error('Gestionnaire de panier non initialisé');
        return false;
    }
}
