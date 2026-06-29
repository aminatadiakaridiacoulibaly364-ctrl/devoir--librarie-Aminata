<?php
include 'connexion.php';
requireLogin();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Plume Numérique — Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>La Plume Numérique</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="catalogue.php">Catalogue</a>
            <a href="panier.php">Panier (<span id="compteur-panier">0</span>)</a>
            <a href="favori.php">Favoris (<span id="compteur-favoris">0</span>)</a>
            <?php if ($user): ?>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="gestion.php" class="lien-gestion">Gestion</a>
                <?php endif; ?>
                <a href="logout.php">Déconnexion (<?= htmlspecialchars($user['username']) ?>)</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="panier-page">
        <section class="panier">
            <h2>Mon panier</h2>
            <div id="liste-panier"></div>
            <p class="total">Total : <span id="total-panier">0.00</span> €</p>
            <button id="btn-valider" class="btn-valider">Valider la commande</button>
            <p id="etat-command" class="etat-command"></p>
        </section>
    </main>

    <footer class="site-footer">
        <p>La Plume Numérique — Librairie en ligne — 2026</p>
    </footer>

    <script src="app.js"></script>
</body>
</html>
