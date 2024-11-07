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
        // Récupération des données du formulaire
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $date_of_birth = $_POST['date_of_birth'];
        $place_of_birth = $_POST['place_of_birth'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        $email_address = $_POST['email_address'];
        $age = $_POST['age'];

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Requête préparée pour insérer l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, password, date_of_birth, place_of_birth, gender, address, phone_number, email_address, age) VALUES (:firstname, :lastname, :password, :date_of_birth, :place_of_birth, :gender, :address, :phone_number, :email_address, :age)");
        
        // Liaison des paramètres
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':place_of_birth', $place_of_birth);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':age', $age);

        // Exécution de la requête et retour d’un message
        if ($stmt->execute()) {
            echo "Utilisateur créé avec succès.";
        } else {
            echo "Erreur lors de la création de l'utilisateur.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!-- Formulaire HTML -->
<form method="post" action="register.php">
    <label>Prénom :</label>
    <input type="text" name="firstname" required>
    <br>
    <label>Nom :</label>
    <input type="text" name="lastname" required>
    <br>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <label>Date de naissance :</label>
    <input type="date" name="date_of_birth" required>
    <br>
    <label>Lieu de naissance :</label>
    <input type="text" name="place_of_birth" required>
    <br>
    <label>Genre :</label>
    <select name="gender" required>
        <option value="M">Masculin</option>
        <option value="F">Féminin</option>
        <option value="O">Autre</option>
    </select>
    <br>
    <label>Adresse :</label>
    <input type="text" name="address" required>
    <br>
    <label>Numéro de téléphone :</label>
    <input type="text" name="phone_number" required>
    <br>
    <label>Adresse e-mail :</label>
    <input type="email" name="email_address" required>
    <br>
    <label>Âge :</label>
    <input type="number" name="age" required>
    <br>
    <button type="submit">S'inscrire</button>
</form>
