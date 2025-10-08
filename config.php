<?php
/**
 * Configuration globale de l'application
 * Détecte automatiquement l'environnement (local ou production)
 */

class AppConfig {
    private static $instance = null;
    private $isLocal;
    private $apiPath;
    
    private function __construct() {
        // Détection de l'environnement
        $this->isLocal = $this->detectLocalEnvironment();
        
        // Définir le chemin API selon l'environnement
        $this->apiPath = $this->isLocal ? '' : 'api/';
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function detectLocalEnvironment() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return (
            strpos($host, 'localhost') !== false ||
            strpos($host, '127.0.0.1') !== false ||
            strpos($host, 'wamp') !== false ||
            strpos($host, 'xampp') !== false ||
            !isset($_SERVER['HTTP_HOST'])
        );
    }
    
    public function isLocal() {
        return $this->isLocal;
    }
    
    public function getApiPath() {
        return $this->apiPath;
    }
    
    public function getBackendPath() {
        return 'backend/';
    }
    
    public function isDebug() {
        return $this->isLocal;
    }
    
    // Chemins vers les APIs
    public function getAdminApi() {
        return $this->apiPath . 'admin_api.php';
    }
    
    public function getCartApi() {
        return $this->backendPath . 'cart_api.php';
    }
    
    public function getFavoritesApi() {
        return $this->backendPath . 'favorites_api.php';
    }
    
    public function getPaymentApi() {
        return $this->backendPath . 'payment_api.php';
    }
    
    // Méthode pour afficher la configuration
    public function display() {
        return [
            'environment' => $this->isLocal ? 'LOCAL' : 'PRODUCTION',
            'api_path' => $this->apiPath,
            'debug' => $this->isDebug(),
            'admin_api' => $this->getAdminApi()
        ];
    }
}

// Fonction helper pour accéder facilement à la config
function app_config() {
    return AppConfig::getInstance();
}

// Fonction helper pour obtenir le chemin API
function api_path($file = '') {
    return app_config()->getApiPath() . $file;
}

