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

    if ($username === '' || $password === '') {
        $error = 'Veuillez renseigner un nom d’utilisateur et un mot de passe.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            loginUser($user);
            if ($user['role'] === 'admin') {
                header('Location: gestion.php');
            } else {
                header('Location: catalogue.php');
            }
            exit();
        }
        $error = 'Nom d’utilisateur ou mot de passe invalide.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — La Plume Numérique</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>La Plume Numérique</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="register.php">Inscription</a>
        </nav>
    </header>

    <main class="gestion">
        <section class="ajout">
            <h2>Connexion</h2>
            <?php if ($error): ?>
                <p class="erreur"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" class="formulaire">
                <input type="text" name="username" placeholder="Nom d’utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <p>La Plume Numérique — Librairie en ligne — 2026</p>
    </footer>
</body>
</html>
