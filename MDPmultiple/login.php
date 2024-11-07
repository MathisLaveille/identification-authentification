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
        $date_of_birth = $_POST['date_of_birth'];
        $place_of_birth = $_POST['place_of_birth'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        $email_address = $_POST['email_address'];

        // Recherche de l'utilisateur dans la base de données avec tous les champs
        $stmt = $pdo->prepare("SELECT * FROM users WHERE firstname = :firstname AND lastname = :lastname AND date_of_birth = :date_of_birth AND place_of_birth = :place_of_birth AND gender = :gender AND address = :address AND phone_number = :phone_number AND email_address = :email_address");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':place_of_birth', $place_of_birth);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($user['firstname']) . "!";
        } else {
            echo "Les informations fournies ne correspondent à aucun utilisateur.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!-- Formulaire HTML et JavaScript -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        /* Style basique pour centrer le formulaire */
        .step { display: none; }
        .step.active { display: block; }
        button { margin-top: 10px; }
    </style>
</head>
<body>
    <form method="post" action="login.php" id="loginForm">
        <!-- Étapes du formulaire -->
        <div class="step active">
            <label>Prénom :</label>
            <input type="text" name="firstname" required>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>
        
        <div class="step">
            <label>Nom :</label>
            <input type="text" name="lastname" required>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>
        
        <div class="step">
            <label>Date de naissance :</label>
            <input type="date" name="date_of_birth" required>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>

        <div class="step">
            <label>Lieu de naissance :</label>
            <input type="text" name="place_of_birth" required>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>

        <div class="step">
            <label>Genre :</label>
            <select name="gender" required>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
                <option value="O">Autre</option>
            </select>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>

        <div class="step">
            <label>Adresse :</label>
            <input type="text" name="address" required>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>

        <div class="step">
            <label>Numéro de téléphone :</label>
            <input type="text" name="phone_number" required>
            <button type="button" onclick="nextStep()">Suivant</button>
        </div>

        <div class="step">
            <label>Adresse e-mail :</label>
            <input type="email" name="email_address" required>
            <button type="submit">Se connecter</button>
        </div>
    </form>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll('.step');

        function nextStep() {
            // Vérifie si le champ actuel est valide avant de passer à l'étape suivante
            const input = steps[currentStep].querySelector('input, select');
            if (input && input.checkValidity()) {
                // Masquer l'étape actuelle
                steps[currentStep].classList.remove('active');
                // Passer à l'étape suivante
                currentStep++;
                if (currentStep < steps.length) {
                    steps[currentStep].classList.add('active');
                }
            } else {
                alert("Veuillez remplir ce champ correctement.");
            }
        }
    </script>
</body>
</html>
