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
            $message = "<div class='alert alert-danger'>Un compte avec cette adresse email existe déjà.</div>";
        } else {

            // Insertion de l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, password, date_of_birth, place_of_birth, gender, address, phone_number, email_address, age) VALUES (:firstname, :lastname, :password, :date_of_birth, :place_of_birth, :gender, :address, :phone_number, :email_address, :age)");
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            $stmt->bindParam(':place_of_birth', $place_of_birth);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':email_address', $email_address);
            $stmt->bindParam(':age', $age);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Inscription réussie ! Vous pouvez maintenant vous connecter.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'inscription. Veuillez réessayer.</div>";
            }
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
    <div class="container col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center text-primary mb-4">Inscription</h2>
                
                <!-- Affichage du message -->
                <?= $message ?>

                <!-- Formulaire d'inscription -->
                <form method="post" action="register.php">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom :</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom :</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe :</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date de naissance :</label>
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="place_of_birth" class="form-label">Lieu de naissance :</label>
                        <input type="text" name="place_of_birth" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Genre :</label>
                        <input type="text" name="gender" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse :</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Numéro de téléphone :</label>
                        <input type="text" name="phone_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_address" class="form-label">Adresse email :</label>
                        <input type="email" name="email_address" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="age" class="form-label">Âge :</label>
                        <input type="number" name="age" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">S'inscrire</button>
                </form>

                <div class="text-center mt-3">
                    <p>Déjà un compte ? <a href="login.php" class="text-decoration-none text-primary">Se connecter</a></p>
                    <p><a href="../index.html" class="text-decoration-none text-primary">Retour</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
