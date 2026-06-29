<?php
include "connexion.php";

// Suppression d'un livre avec une requête préparée (DELETE)
$req = $pdo->prepare("DELETE FROM livres WHERE id = ?");
$req->execute([$_GET["id"]]);

// Retour à la page de gestion
header("Location: gestion.php");
exit();
?>
