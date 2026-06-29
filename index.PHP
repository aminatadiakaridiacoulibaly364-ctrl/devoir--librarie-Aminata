<?php
include 'connexion.php';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La plume Numérique</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header" >
    <h1>La plume Numérique</h1>
    <nav>
     <a href="index.PHP">Accueil</a>
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

 <main class="accueil">
        <section class="hero">
            <h2>Bienvenue dans votre librairie en ligne</h2>
            <p>
                La Plume Numérique vous propose une sélection de livres pour tous les goûts :
                romans, policiers, bandes dessinées et bien plus encore.
                Parcourez notre catalogue et composez votre panier en quelques clics.
            </p>
            <a href="catalogue.php" class="btn">Découvrir le catalogue</a>
        </section>
    </main>

    <footer class="site-footer">
        <p>La Plume Numérique — Librairie en ligne — 2026</p>
    </footer>




  <script src="app.js"></script>
</body>
</html>