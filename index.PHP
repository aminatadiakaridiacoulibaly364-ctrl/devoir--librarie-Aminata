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
     <a href="panier.html">Panier (<span id="compteur-panier">0</span>)</a>
     <a href="favori.html">Favoris (<span id="compteur-favoris">0</span>)</a>
     <a href="gestion.php" class="lien-gestion"><button>Gestion</button></a>
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