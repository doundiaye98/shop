// JavaScript pour la navbar e-commerce moderne

class ECommerceNavbar {
    constructor() {
        this.init();
    }

    init() {
        this.setupMobileMenu();
        this.setupSearch();
        this.setupMobileDropdowns();
        this.setupCartCounter();
        this.setupMegaMenus();
    }

    // Menu mobile - CORRECTION COMPLÈTE
    setupMobileMenu() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileClose = document.getElementById('mobileMenuClose');
        const mobileOverlay = document.getElementById('mobileOverlay');

        console.log('🔧 Setup menu mobile...', {
            mobileToggle: !!mobileToggle,
            mobileMenu: !!mobileMenu,
            mobileOverlay: !!mobileOverlay
        });

        if (mobileToggle && mobileMenu && mobileOverlay) {
            // Ouvrir le menu
            mobileToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('🍔 Clic sur hamburger détecté !');
                
                // Ajouter les classes active
                mobileMenu.classList.add('active');
                mobileOverlay.classList.add('active');
                mobileToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Animation du burger vers X
                const lines = mobileToggle.querySelectorAll('.burger-line');
                console.log('🔧 Animation des barres...', lines.length);
                
                if (lines.length >= 3) {
                    lines[0].style.transform = 'rotate(45deg) translate(6px, 6px)';
                    lines[0].style.background = '#333';
                    lines[1].style.opacity = '0';
                    lines[2].style.transform = 'rotate(-45deg) translate(6px, -6px)';
                    lines[2].style.background = '#333';
                }
            });

            // Fermer le menu - CORRECTION COMPLÈTE
            const closeMenu = () => {
                console.log('🚪 Fermeture du menu mobile...');
                
                mobileMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                mobileToggle.classList.remove('active');
                document.body.style.overflow = '';
                
                // Reset burger animation vers hamburger
                const lines = mobileToggle.querySelectorAll('.burger-line');
                if (lines.length >= 3) {
                    lines[0].style.transform = '';
                    lines[0].style.background = '#333';
                    lines[1].style.opacity = '1';
                    lines[2].style.transform = '';
                    lines[2].style.background = '#333';
                }
            };

            if (mobileClose) {
                mobileClose.addEventListener('click', closeMenu);
            }
            
            mobileOverlay.addEventListener('click', closeMenu);

            // Fermer avec Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    closeMenu();
                }
            });
        }
    }

    // Recherche
    setupSearch() {
        const searchToggle = document.getElementById('searchToggle');
        const searchDropdown = document.getElementById('searchDropdown');
        const searchInput = searchDropdown?.querySelector('.search-input');

        if (searchToggle && searchDropdown) {
            // Toggle search dropdown
            searchToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                searchDropdown.style.opacity = searchDropdown.style.opacity === '1' ? '0' : '1';
                searchDropdown.style.visibility = searchDropdown.style.visibility === 'visible' ? 'hidden' : 'visible';
                searchDropdown.style.transform = searchDropdown.style.transform === 'translateY(0px)' ? 'translateY(-10px)' : 'translateY(0px)';
                
                if (searchInput && searchDropdown.style.visibility === 'visible') {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });

            // Fermer en cliquant ailleurs
            document.addEventListener('click', (e) => {
                if (!searchToggle.contains(e.target) && !searchDropdown.contains(e.target)) {
                    searchDropdown.style.opacity = '0';
                    searchDropdown.style.visibility = 'hidden';
                    searchDropdown.style.transform = 'translateY(-10px)';
                }
            });

            // Recherche en temps réel (optionnel)
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    const query = e.target.value.trim();
                    
                    if (query.length >= 2) {
                        searchTimeout = setTimeout(() => {
                            this.performSearch(query);
                        }, 300);
                    }
                });
            }
        }
    }

    // Dropdowns mobiles
    setupMobileDropdowns() {
        const dropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
        
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const dropdown = toggle.parentElement;
                const icon = toggle.querySelector('.dropdown-icon');
                
                // Fermer les autres dropdowns
                dropdownToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        const otherDropdown = otherToggle.parentElement;
                        const otherIcon = otherToggle.querySelector('.dropdown-icon');
                        otherDropdown.classList.remove('active');
                        if (otherIcon) otherIcon.style.transform = '';
                    }
                });
                
                // Toggle current dropdown
                dropdown.classList.toggle('active');
                if (icon) {
                    icon.style.transform = dropdown.classList.contains('active') ? 'rotate(180deg)' : '';
                }
            });
        });
    }

    // Compteur panier
    setupCartCounter() {
        this.updateCartCounter();
        
        // Écouter les changements du panier
        document.addEventListener('cartUpdated', () => {
            this.updateCartCounter();
        });
    }

    updateCartCounter() {
        const cartCount = document.getElementById('cartCount');
        if (cartCount) {
            const count = this.getCartItemCount();
            cartCount.textContent = count;
            cartCount.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    getCartItemCount() {
        // Intégration avec votre système de panier
        if (typeof getCartItemCount === 'function') {
            return getCartItemCount();
        }
        
        // Fallback: utiliser localStorage
        try {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            return cart.reduce((total, item) => total + (item.quantity || 1), 0);
        } catch {
            return 0;
        }
    }

    // Mega menus (animations supplémentaires)
    setupMegaMenus() {
        const megaDropdowns = document.querySelectorAll('.mega-dropdown');
        
        megaDropdowns.forEach(dropdown => {
            const megaMenu = dropdown.querySelector('.mega-menu');
            let hoverTimeout;
            
            if (megaMenu) {
                dropdown.addEventListener('mouseenter', () => {
                    clearTimeout(hoverTimeout);
                    megaMenu.style.opacity = '1';
                    megaMenu.style.visibility = 'visible';
                    megaMenu.style.transform = 'translateY(0)';
                });
                
                dropdown.addEventListener('mouseleave', () => {
                    hoverTimeout = setTimeout(() => {
                        megaMenu.style.opacity = '0';
                        megaMenu.style.visibility = 'hidden';
                        megaMenu.style.transform = 'translateY(-10px)';
                    }, 100);
                });
            }
        });
    }

    // Recherche en temps réel (à adapter selon votre API)
    async performSearch(query) {
        try {
            // Exemple d'appel API - à adapter selon votre backend
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            if (response.ok) {
                const results = await response.json();
                this.displaySearchResults(results);
            }
        } catch (error) {
            console.log('Recherche désactivée:', error.message);
            // La recherche en temps réel est optionnelle
        }
    }

    displaySearchResults(results) {
        // Afficher les résultats dans le dropdown de recherche
        const searchDropdown = document.getElementById('searchDropdown');
        if (!searchDropdown) return;
        
        let resultsContainer = searchDropdown.querySelector('.search-results');
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.className = 'search-results';
            searchDropdown.appendChild(resultsContainer);
        }
        
        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="search-no-results">Aucun résultat trouvé</div>';
            return;
        }
        
        resultsContainer.innerHTML = results.slice(0, 5).map(result => `
            <a href="product_detail.php?id=${result.id}" class="search-result-item">
                <img src="${result.image || 'https://via.placeholder.com/40x40'}" alt="${result.name}">
                <div>
                    <div class="search-result-name">${result.name}</div>
                    <div class="search-result-price">${result.price}€</div>
                </div>
            </a>
        `).join('');
    }

    // Mini panier
    setupMiniCart() {
        const cartContainer = document.querySelector('.cart-container');
        const miniCart = document.getElementById('miniCart');
        const miniCartContent = document.getElementById('miniCartContent');
        
        if (cartContainer && miniCart) {
            cartContainer.addEventListener('mouseenter', () => {
                this.loadMiniCartContent();
            });
        }
    }

    async loadMiniCartContent() {
        const miniCartContent = document.getElementById('miniCartContent');
        if (!miniCartContent) return;
        
        try {
            // Charger le contenu du panier - à adapter selon votre système
            const cart = this.getCartItems();
            
            if (cart.length === 0) {
                miniCartContent.innerHTML = '<div class="mini-cart-empty">Votre panier est vide</div>';
                return;
            }
            
            const cartHTML = cart.slice(0, 3).map(item => `
                <div class="mini-cart-item">
                    <img src="${item.image}" alt="${item.name}" class="mini-cart-item-image">
                    <div class="mini-cart-item-info">
                        <div class="mini-cart-item-name">${item.name}</div>
                        <div class="mini-cart-item-price">${item.quantity}x ${item.price}€</div>
                    </div>
                </div>
            `).join('');
            
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            miniCartContent.innerHTML = `
                ${cartHTML}
                ${cart.length > 3 ? `<div class="mini-cart-more">+${cart.length - 3} autre(s) produit(s)</div>` : ''}
                <div class="mini-cart-total">Total: ${total.toFixed(2)}€</div>
            `;
            
        } catch (error) {
            console.error('Erreur lors du chargement du mini panier:', error);
        }
    }

    getCartItems() {
        // Intégration avec votre système de panier
        if (typeof getCartItems === 'function') {
            return getCartItems();
        }
        
        // Fallback: utiliser localStorage
        try {
            return JSON.parse(localStorage.getItem('cart') || '[]');
        } catch {
            return [];
        }
    }
}

// Initialiser la navbar quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.ecommerceNavbar = new ECommerceNavbar();
});

// Styles CSS supplémentaires pour les résultats de recherche
const searchStyles = `
<style>
.search-results {
    border-top: 1px solid #e5e5e5;
    max-height: 300px;
    overflow-y: auto;
}

.search-result-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    text-decoration: none;
    color: #333;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.3s ease;
}

.search-result-item:hover {
    background: #f8f9fa;
}

.search-result-item img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 12px;
}

.search-result-name {
    font-weight: 500;
    margin-bottom: 2px;
}

.search-result-price {
    font-size: 14px;
    color: #e74c3c;
    font-weight: 600;
}

.search-no-results {
    padding: 20px;
    text-align: center;
    color: #666;
    font-style: italic;
}

.mini-cart-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.mini-cart-item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 12px;
}

.mini-cart-item-name {
    font-weight: 500;
    margin-bottom: 2px;
    font-size: 14px;
}

.mini-cart-item-price {
    font-size: 12px;
    color: #666;
}

.mini-cart-more {
    padding: 10px 0;
    text-align: center;
    font-size: 12px;
    color: #666;
    font-style: italic;
}

.mini-cart-total {
    padding: 15px 0 0 0;
    margin-top: 10px;
    border-top: 1px solid #e5e5e5;
    font-weight: 600;
    text-align: right;
    color: #2c3e50;
}
</style>
`;

// Injecter les styles
document.head.insertAdjacentHTML('beforeend', searchStyles);
