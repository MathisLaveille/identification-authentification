<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: login.php");
    exit();
}

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
        // Récupérer le nouveau mot de passe et le mot de passe actuel
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $username = $_SESSION['username'];

        // Vérifier que le nouveau mot de passe et la confirmation correspondent
        if ($newPassword !== $confirmPassword) {
            echo "Les mots de passe ne correspondent pas.";
        } else {
            // Récupérer l'utilisateur et le mot de passe actuel depuis la base de données
            $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier que le mot de passe actuel est correct
            if ($user && password_verify($currentPassword, $user['password'])) {
                // Hacher le nouveau mot de passe
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Mettre à jour le mot de passe dans la base de données
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':username', $username);
                
                if ($stmt->execute()) {
                    echo "Le mot de passe a été mis à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour du mot de passe.";
                }
            } else {
                echo "Mot de passe actuel incorrect.";
            }
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres du compte</title>
</head>
<body>
    <h1>Paramètres du compte</h1>
    <p>Changer votre mot de passe :</p>

    <form method="post" action="parametres.php">
        <label>Mot de passe actuel :</label>
        <input type="password" name="current_password" required>
        <br>
        <label>Nouveau mot de passe :</label>
        <input type="password" name="new_password" required>
        <br>
        <label>Confirmer le nouveau mot de passe :</label>
        <input type="password" name="confirm_password" required>
        <br>
        <button type="submit">Mettre à jour le mot de passe</button>
    </form>

    <br>
    <a href="tableau_de_bord.php">Retour au tableau de bord</a>
</body>
</html>
