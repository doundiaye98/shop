// Configuration globale pour l'application
// Détecte automatiquement l'environnement (local ou production)

const AppConfig = {
    // Détection de l'environnement
    isLocal: window.location.hostname === 'localhost' || 
             window.location.hostname === '127.0.0.1' ||
             window.location.hostname.includes('wamp') ||
             window.location.hostname.includes('xampp'),
    
    // Configuration des chemins API
    get apiPath() {
        // En local : utiliser les chemins directs
        // En production : utiliser le dossier api/
        return this.isLocal ? '' : 'api/';
    },
    
    // Endpoints API
    api: {
        admin: function() { return AppConfig.apiPath + 'admin.php'; },
        cart: function() { return AppConfig.apiPath + 'cart.php'; },
        favorites: function() { return AppConfig.apiPath + 'favorites.php'; },
        payment: function() { return AppConfig.apiPath + 'payment.php'; },
        passwordReset: function() { return AppConfig.apiPath + 'password_reset.php'; },
        directPurchase: function() { return AppConfig.apiPath + 'direct_purchase.php'; },
    },
    
    // Chemin vers le backend
    get backendPath() {
        return 'backend/';
    },
    
    // Configuration du mode debug
    get debug() {
        return this.isLocal;
    },
    
    // Log si en mode debug
    log: function(message, ...args) {
        if (this.debug) {
            console.log(`[AppConfig] ${message}`, ...args);
        }
    },
    
    // Initialisation
    init: function() {
        this.log('Environment:', this.isLocal ? 'LOCAL' : 'PRODUCTION');
        this.log('API Path:', this.apiPath);
        this.log('Admin API:', this.api.admin());
        this.log('Cart API:', this.api.cart());
        
        // Exposer globalement pour faciliter l'accès
        window.API_CONFIG = this;
    }
};

// Auto-initialisation
if (typeof window !== 'undefined') {
    AppConfig.init();
}

// Export pour modules ES6 (si utilisé)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AppConfig;
}
