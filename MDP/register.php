<?php
// Configuration de la connexion
$host = 'localhost';
$dbname = 'identificationauthentification';
$username = 'root';
$password = 'root';

$message = ''; // Variable pour stocker les messages

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstname = $_POST['firstname'];
        $password = $_POST['password'];

        // Requête préparée pour insérer l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (firstname, password) VALUES (:firstname, :password)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Utilisateur créé avec succès. <a href='login.php'>Connectez-vous ici</a>.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la création de l'utilisateur. Veuillez réessayer.</div>";
        }
    }
} catch (PDOException $e) {
    $message = "<div class='alert alert-danger'>Erreur de connexion : " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="container col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center text-primary mb-4">Inscription</h2>
                <!-- Message d'erreur ou de succès -->
                <?= $message ?>
                <!-- Formulaire HTML -->
                <form method="post" action="register.php">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Nom d'utilisateur :</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Déjà inscrit ? <a href="login.php" class="text-decoration-none text-primary">Connectez-vous ici</a>.</p>
                    <p><a href="../index.html" class="text-decoration-none text-primary">Retour</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
