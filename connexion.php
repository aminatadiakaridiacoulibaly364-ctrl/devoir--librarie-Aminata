<?php
session_start();

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function requireLogin() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (empty($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: login.php');
        exit();
    }
}

function loginUser(array $user) {
    unset($user['password']);
    $_SESSION['user'] = $user;
}

// Connexion à la base de données avec PDO
$hote = "localhost";
$base = "librairie";
$user = "root";   // identifiant par défaut XAMPP
$mdp  = "";       // mot de passe vide par défaut

try {
    $pdo = new PDO("mysql:host=$hote;dbname=$base;charset=utf8mb4", $user, $mdp);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Création de la table users si nécessaire
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','client') NOT NULL DEFAULT 'client',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );

    // Créer un compte admin par défaut si aucun n'existe
    $adminExists = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
    if (!$adminExists) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
        $stmt->execute(['admin', $hash]);
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
?>
