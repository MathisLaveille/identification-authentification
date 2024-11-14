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

        // Vérification que l'email n'existe pas déjà dans la base de données
        $checkEmail = $pdo->prepare("SELECT * FROM users WHERE email_address = :email_address");
        $checkEmail->bindParam(':email_address', $email_address);
        $checkEmail->execute();

        if ($checkEmail->rowCount() > 0) {
            echo "Un compte avec cette adresse email existe déjà.";
        } else {
            // Hachage du mot de passe pour la sécurité
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insertion de l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, password, date_of_birth, place_of_birth, gender, address, phone_number, email_address, age) VALUES (:firstname, :lastname, :password, :date_of_birth, :place_of_birth, :gender, :address, :phone_number, :email_address, :age)");
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

            if ($stmt->execute()) {
                echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                echo "Erreur lors de l'inscription. Veuillez réessayer.";
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
    <title>Inscription</title>
    <style>
        .input-field {
            display: block;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post" action="register.php">
        <div class="form-group">
            <label>Prénom :</label>
            <input type="text" name="firstname" class="input-field" required>
        </div>
        
        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="lastname" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Mot de passe :</label>
            <input type="password" name="password" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Date de naissance :</label>
            <input type="date" name="date_of_birth" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Lieu de naissance :</label>
            <input type="text" name="place_of_birth" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Genre :</label>
            <input type="text" name="gender" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Adresse :</label>
            <input type="text" name="address" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Numéro de téléphone :</label>
            <input type="text" name="phone_number" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Adresse email :</label>
            <input type="email" name="email_address" class="input-field" required>
        </div>

        <div class="form-group">
            <label>Âge :</label>
            <input type="number" name="age" class="input-field" required>
        </div>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
