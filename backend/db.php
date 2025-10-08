<?php
// Configuration de la base de données
// Pour la production, modifiez ces valeurs selon votre hébergeur

// Configuration locale (développement)
$is_local = !isset($_SERVER['HTTP_HOST']) || 
           $_SERVER['HTTP_HOST'] === 'localhost' || 
           $_SERVER['HTTP_HOST'] === '127.0.0.1';

if ($is_local) {
    $host = 'localhost';
    $db   = 'shop';
    $user = 'root';
    $pass = 'root';
} else {
    // Configuration production - À MODIFIER selon votre hébergeur
    $host = 'localhost'; // Ou l'adresse de votre serveur MySQL
    $db   = 'votre_nom_db'; // Nom de votre base de données
    $user = 'votre_utilisateur'; // Utilisateur de la base de données
    $pass = 'votre_mot_de_passe'; // Mot de passe de la base de données
}

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // En production, ne pas afficher les détails d'erreur
    if ($is_local) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    } else {
        error_log("Erreur de connexion à la base de données: " . $e->getMessage());
        die("Erreur de connexion à la base de données");
    }
}

// Fonction pour échapper les données
function escape_string($string) {
    global $pdo;
    return $pdo->quote($string);
}

// Fonction pour exécuter une requête sécurisée
function execute_query($sql, $params = []) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare($sql);
        if ($stmt) {
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        // Log the error or handle it appropriately
        error_log("Database query error: " . $e->getMessage());
        return false;
    }
    
    return false;
}

// Fonction pour obtenir une seule ligne
function get_single_row($sql, $params = []) {
    $results = execute_query($sql, $params);
    if ($results && count($results) > 0) {
        return $results[0];
    }
    return null;
}

// Fonction pour obtenir plusieurs lignes
function get_multiple_rows($sql, $params = []) {
    $results = execute_query($sql, $params);
    return $results;
}

// Fonction pour insérer des données
function insert_data($table, $data) {
    global $pdo;
    
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
    try {
        $stmt = $pdo->prepare($sql);
        if ($stmt) {
            $stmt->execute(array_values($data));
            return $pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        error_log("Database insert error: " . $e->getMessage());
        return false;
    }
    
    return false;
}

// Fonction pour mettre à jour des données
function update_data($table, $data, $where, $where_params = []) {
    global $pdo;
    
    $set_clause = implode(' = ?, ', array_keys($data)) . ' = ?';
    $sql = "UPDATE $table SET $set_clause WHERE $where";
    
    try {
        $stmt = $pdo->prepare($sql);
        if ($stmt) {
            $params = array_merge(array_values($data), $where_params);
            $stmt->execute($params);
            return true;
        }
    } catch (PDOException $e) {
        error_log("Database update error: " . $e->getMessage());
        return false;
    }
    
    return false;
}

// Fonction pour supprimer des données
function delete_data($table, $where, $params = []) {
    global $pdo;
    
    $sql = "DELETE FROM $table WHERE $where";
    
    try {
        $stmt = $pdo->prepare($sql);
        if ($stmt) {
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return true;
        }
    } catch (PDOException $e) {
        error_log("Database delete error: " . $e->getMessage());
        return false;
    }
    
    return false;
}

// Fonction pour compter les enregistrements
function count_records($table, $where = '1', $params = []) {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE $where";
    $result = get_single_row($sql, $params);
    return $result ? $result['count'] : 0;
}

// Fonction pour vérifier si un enregistrement existe
function record_exists($table, $where, $params = []) {
    return count_records($table, $where, $params) > 0;
}

// Fonction pour fermer la connexion
function close_connection() {
    global $pdo;
    $pdo = null; // Close the PDO connection
}

// Fermer la connexion à la fin du script
register_shutdown_function('close_connection');
?> 