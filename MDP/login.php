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
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Requête préparée pour récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($username) . "!";
            // Ici, on peut démarrer une session ou rediriger l'utilisateur
            session_start();
            $_SESSION['username'] = $username;
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
    <input type="text" name="username" required>
    <br>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>