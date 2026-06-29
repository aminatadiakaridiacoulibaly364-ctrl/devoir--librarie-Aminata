<?php
include "connexion.php";
// requireAdmin();
$user = currentUser();

// --- AJOUT & MODIFICATION d'un livre ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["id"])) {
        $req = $pdo->prepare(
            "UPDATE livres SET titre = ?, auteur = ?, prix = ?, genre = ?, image = ? WHERE id = ?"
        );
        $req->execute([
            $_POST["titre"],
            $_POST["auteur"],
            $_POST["prix"],
            $_POST["genre"],
            $_POST["image"],
            $_POST["id"]
        ]);
    } else {
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
    }
    // Rediriger pour éviter le renvoi du formulaire au rechargement
    header("Location: gestion.php");
    exit();
}

$edition = null;
if (!empty($_GET["edit"])) {
    $req = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $req->execute([$_GET["edit"]]);
    $edition = $req->fetch();
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>La Plume Numérique — Gestion</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="catalogue.php">Catalogue</a>
            <a href="panier.php">Panier (<span id="compteur-panier">0</span>)</a>
            <a href="favori.php">Favoris (<span id="compteur-favoris">0</span>)</a>
            <a href="gestion.php" class="lien-gestion">Gestion</a>
            <a href="logout.php">Déconnexion (<?= htmlspecialchars($user['username']) ?>)</a>
        </nav>
    </header>

    <main class="gestion">
        <section class="ajout">
            <h2><?= $edition ? 'Modifier un livre' : 'Ajouter un livre' ?></h2>
            <form method="POST" class="formulaire">
                <input type="hidden" name="id" value="<?= $edition ? htmlspecialchars($edition['id']) : '' ?>">
                <input type="text" name="titre" placeholder="Titre" value="<?= $edition ? htmlspecialchars($edition['titre']) : '' ?>" required>
                <input type="text" name="auteur" placeholder="Auteur" value="<?= $edition ? htmlspecialchars($edition['auteur']) : '' ?>" required>
                <input type="number" name="prix" placeholder="Prix" step="0.01" value="<?= $edition ? htmlspecialchars($edition['prix']) : '' ?>" required>
                <input type="text" name="genre" placeholder="Genre" value="<?= $edition ? htmlspecialchars($edition['genre']) : '' ?>">
                <input type="text" name="image" placeholder="Chemin image (ex: images/livre.jpg)" value="<?= $edition ? htmlspecialchars($edition['image']) : '' ?>">
                <button type="submit"><?= $edition ? 'Enregistrer' : 'Ajouter' ?></button>
                <?php if ($edition) { ?>
                    <a href="gestion.php" class="btn-annuler">Annuler</a>
                <?php } ?>
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
                                <a href="gestion.php?edit=<?= $livre['id'] ?>" class="btn-edit">Modifier</a>
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
    <script src="app.js"></script>
</body>
</html>
