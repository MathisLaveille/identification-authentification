<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: login.php");
    exit();
}

// Récupérer le nom d'utilisateur depuis la session
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue, <?php echo $username; ?> !</h1>
    <p>Vous êtes connecté à votre tableau de bord.</p>

    <nav>
        <ul>
            <li><a href="parametres.php">Paramètres du compte</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</body>
</html>
