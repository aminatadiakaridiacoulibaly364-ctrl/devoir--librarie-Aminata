<?php
// Connexion à la base de données avec PDO
$hote = "localhost";
$base = "librairie";
$user = "root";   // identifiant par défaut XAMPP
$mdp  = "";       // mot de passe vide par défaut

try {
    $pdo = new PDO("mysql:host=$hote;dbname=$base;charset=utf8", $user, $mdp);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
