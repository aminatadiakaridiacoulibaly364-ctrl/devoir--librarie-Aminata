<?php
include "connexion.php";
requireLogin();
$user = currentUser();
// Récupérer tous les livres depuis la base
$livres = $pdo->query("SELECT * FROM livres")->fetchAll();
$genres = $pdo->query("SELECT DISTINCT genre FROM livres ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Plume Numérique — Catalogue</title>
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
            <?php else: ?>
                <a href="login.php">Connexion</a>
                <a href="register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="catalogue">
            <h2>Notre catalogue</h2>
            <div class="filtres-catalogue">
                <input id="search-titre" type="search" placeholder="Rechercher un titre...">
                <select id="filter-genre">
                    <option value="">Tous les genres</option>
                    <?php foreach ($genres as $genre) {
                        if (trim($genre) === '') continue;
                    ?>
                        <option value="<?= htmlspecialchars($genre) ?>"><?= htmlspecialchars($genre) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grille-livres">
                <?php foreach ($livres as $livre) { ?>
                    <article class="carte-livre"
                             data-id="<?php echo $livre['id']; ?>"
                             data-titre="<?= htmlspecialchars($livre['titre']) ?>"
                             data-auteur="<?= htmlspecialchars($livre['auteur']) ?>"
                             data-prix="<?= $livre['prix'] ?>"
                             data-genre="<?= htmlspecialchars($livre['genre']) ?>"
                             data-image="<?= htmlspecialchars($livre['image']) ?>">
                        <img src="<?= htmlspecialchars($livre['image']) ?>"
                             alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>"
                             class="couverture">
                        <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                        <p class="auteur"><?= htmlspecialchars($livre['auteur']) ?></p>
                        <span class="genre"><?= htmlspecialchars($livre['genre']) ?></span>
                        <p class="prix"><?= number_format($livre['prix'], 2) ?> €</p>
                        <div class="carte-actions">
                            <button class="btn-ajouter" type="button">Ajouter au panier</button>
                            <button class="btn-heart" type="button" aria-label="Ajouter aux favoris">❤</button>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>

        <!-- <section id="panier" class="panier">
            <h2>Mon panier</h2>
            <div id="liste-panier"></div>
            <p class="total">Total : <span id="total-panier">0.00</span> €</p>
        </section> -->
    </main>

    <footer class="site-footer">
        <p>La Plume Numérique — Librairie en ligne — 2026</p>
    </footer>

    <script src="app.js"></script>
</body>
</html>
