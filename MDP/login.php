<?php
// Configuration de la connexion
$host = 'localhost';
$dbname = 'identificationauthentification';
$username = 'root';
$password = 'root';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstname = $_POST['firstname'];
        $password = $_POST['password'];

        // Requête préparée pour récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE firstname = :firstname");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($firstname) . "!";
            // Ici, on peut démarrer une session ou rediriger l'utilisateur
            session_start();
            $_SESSION['firstname'] = $firstname;
            header("Location: tableau_de_bord.php"); // Rediriger vers une page de tableau de bord
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!-- Formulaire HTML -->
<form method="post" action="login.php">
    <label>Nom d'utilisateur :</label>
    <input type="text" name="firstname" required>
    <br>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>