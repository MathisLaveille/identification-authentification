<?php

// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'identificationauthentification';
$username = 'root';
$password = 'root';

$message = ''; // Variable pour stocker les messages

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $firstname = $_POST['firstname'];
        $password = $_POST['password'];

        // Requête préparée pour vérifier les identifiants
        $stmt = $pdo->prepare("SELECT * FROM users WHERE firstname = :firstname AND password = :password");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe
        if ($user) {
            $message = "<div class='alert alert-success'>Connexion réussie. Bienvenue, " . htmlspecialchars($firstname) . ".</div>";
        } else {
            $message = "<div class='alert alert-danger'>Nom d'utilisateur ou mot de passe incorrect.</div>";
        }
    }
} catch (PDOException $e) {
    $message = "<div class='alert alert-danger'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="container col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center text-primary mb-4">Connexion</h2>
                <!-- Affichage du message -->
                <?= $message ?>
                <!-- Formulaire de connexion -->
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Nom d'utilisateur :</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Se connecter</button>
                    </div>
                </form>
                <!-- Lien vers le formulaire d'inscription -->
                <div class="text-center mt-3">
                    <p>Pas encore inscrit ? <a href="register.php" class="text-decoration-none text-primary">Créer un compte</a></p>
                    <p><a href="../index.html" class="text-decoration-none text-primary">Retour</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
