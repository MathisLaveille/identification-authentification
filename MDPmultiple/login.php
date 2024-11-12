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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        .form-group {
            margin-bottom: 15px;
            display: none;
        }
        #firstnameField { display: block; }
        #uselessFieldsContainer {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        .useless-field {
            margin-top: 10px;
            display: block;
            width: 100%; /* Pour s'aligner avec les autres champs */
        }
    </style>
</head>
<body>
    <form method="post" action="login.php" id="loginForm">
        <div id="formFields">
            <div class="form-group" id="firstnameField">
                <input type="text" name="firstname" required onfocus="showNextField('lastnameField')">
            </div>

            <div class="form-group" id="lastnameField">
                <input type="text" name="lastname" required onfocus="showNextField('dateOfBirthField')">
            </div>

            <div class="form-group" id="dateOfBirthField">
                <input type="text" name="date_of_birth" required onfocus="showNextField('placeOfBirthField')">
            </div>

            <div class="form-group" id="placeOfBirthField">
                <input type="text" name="place_of_birth" required onfocus="showNextField('genderField')">
            </div>

            <div class="form-group" id="genderField">
                <input type="text" name="place_of_birth" required onfocus="showNextField('addressField')">
            </div>

            <div class="form-group" id="addressField">
                <input type="text" name="address" required onfocus="showNextField('phoneNumberField')">
            </div>

            <div class="form-group" id="phoneNumberField">
                <input type="text" name="phone_number" required onfocus="showNextField('emailAddressField')">
            </div>

            <div class="form-group" id="emailAddressField">
                <input type="email" name="email_address" required onfocus="showConnectButton()">
            </div>
        </div>
        
        <div id="uselessFieldsContainer"></div>

        <div id="submitBtn">
            <button type="submit">Se connecter</button>
        </div>

        <!-- Conteneur pour les champs "inutiles" -->
        
    </form>

    <script>
        function showNextField(nextFieldId) {
            document.getElementById(nextFieldId).style.display = 'block';
        }

        function showConnectButton() {
            document.getElementById('submitBtn').style.display = 'block';
            // Une fois le formulaire principal rempli, activer l'ajout de champs inutiles
            addUselessField();
        }

        function addUselessField() {
            // Créer un champ de saisie supplémentaire
            const container = document.getElementById('uselessFieldsContainer');
            const newField = document.createElement('input');
            newField.type = 'text';
            newField.className = 'useless-field';
            container.appendChild(newField);

            // Ajouter un événement au clic pour ajouter un autre champ inutile
            newField.addEventListener('focus', () => {
                addUselessField();
            });
        }
    </script>
</body>
</html>
