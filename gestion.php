<?php
include "connexion.php";

// --- AJOUT d'un livre (CREATE) ---
if (isset($_POST["titre"])) {
    $req = $pdo->prepare(
        "INSERT INTO livres (titre, auteur, prix, genre, image)
         VALUES (?, ?, ?, ?, ?)"
    );
    $req->execute([
        $_POST["titre"],
        $_POST["auteur"],
        $_POST["prix"],
        $_POST["genre"],
        $_POST["image"]
    ]);
    // Rediriger pour éviter le renvoi du formulaire au rechargement
    header("Location: gestion.php");
    exit();
}

// Récupérer la liste des livres pour l'affichage
$livres = $pdo->query("SELECT * FROM livres")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Plume Numérique — Gestion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <h1>La Plume Numérique — Gestion</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="catalogue.php">Catalogue</a>
            <a href="gestion.php" class="lien-gestion">Gestion</a>
        </nav>
    </header>

    <main class="gestion">
        <section class="ajout">
            <h2>Ajouter un livre</h2>
            <form method="POST" class="formulaire">
                <input type="text" name="titre" placeholder="Titre" required>
                <input type="text" name="auteur" placeholder="Auteur" required>
                <input type="number" name="prix" placeholder="Prix" step="0.01" required>
                <input type="text" name="genre" placeholder="Genre">
                <input type="text" name="image" placeholder="Chemin image (ex: images/livre.jpg)">
                <button type="submit">Ajouter</button>
            </form>
        </section>

        <section class="liste">
            <h2>Liste des livres</h2>
            <table class="table-livres">
                <thead>
                    <tr><th>Titre</th><th>Auteur</th><th>Prix</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($livres as $livre) { ?>
                        <tr>
                            <td><?= htmlspecialchars($livre['titre']) ?></td>
                            <td><?= htmlspecialchars($livre['auteur']) ?></td>
                            <td><?= number_format($livre['prix'], 2) ?> €</td>
                            <td>
                                <a href="supprimer.php?id=<?= $livre['id'] ?>"
                                   class="btn-supprimer"
                                   onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="site-footer">
        <p>La Plume Numérique — Espace de gestion</p>
    </footer>
</body>
</html>
