<?php
include 'connexion.php';
if (currentUser()) {
    header('Location: index.php');
    exit();
}
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($username === '' || $password === '' || $confirm === '') {
        $error = 'Tous les champs sont obligatoires.';
    } elseif ($password !== $confirm) {
        $error = 'Les mots de passe doivent correspondre.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Ce nom d’utilisateur est déjà utilisé.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)' );
            $stmt->execute([$username, $hash, 'client']);
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            loginUser($stmt->fetch());
            header('Location: catalogue.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — La Plume Numérique</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>La Plume Numérique</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="login.php">Connexion</a>
        </nav>
    </header>

    <main class="gestion">
        <section class="ajout">
            <h2>Créer un compte</h2>
            <?php if ($error): ?>
                <p class="erreur"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" class="formulaire">
                <input type="text" name="username" placeholder="Nom d’utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="confirm" placeholder="Confirmer mot de passe" required>
                <button type="submit">S’inscrire</button>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <p>La Plume Numérique — Librairie en ligne — 2026</p>
    </footer>
</body>
</html>
