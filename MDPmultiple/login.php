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

        // Recherche de l'utilisateur dans la base de données avec tous les champs
        $stmt = $pdo->prepare("SELECT * FROM users WHERE firstname = :firstname AND lastname = :lastname AND email_address = :email_address");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification si l'utilisateur existe et si le mot de passe correspond
        if ($user && password_verify($password, $user['password'])) {
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
        #uselessFieldsContainer {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <form method="post" action="login.php" id="loginForm">
        <div id="formFields">
            <div class="form-group" id="firstnameField">
                <input type="text" name="firstname" class="input-field" required onfocus="showNextField('lastnameField')">
            </div>

            <div class="form-group" id="lastnameField">
                <input type="text" name="lastname" class="input-field" required onfocus="showNextField('passwordField')">
            </div>

            <div class="form-group" id="passwordField">
                <input type="password" name="password" class="input-field" required onfocus="showNextField('dateOfBirthField')">
            </div>

            <div class="form-group" id="dateOfBirthField">
                <input type="text" name="date_of_birth" class="input-field" required onfocus="showNextField('placeOfBirthField')">
            </div>

            <div class="form-group" id="placeOfBirthField">
                <input type="text" name="place_of_birth" class="input-field" required onfocus="showNextField('genderField')">
            </div>

            <div class="form-group" id="genderField">
                <input type="text" name="gender" class="input-field" required onfocus="showNextField('addressField')">
            </div>

            <div class="form-group" id="addressField">
                <input type="text" name="address" class="input-field" required onfocus="showNextField('phoneNumberField')">
            </div>

            <div class="form-group" id="phoneNumberField">
                <input type="text" name="phone_number" class="input-field" required onfocus="showNextField('emailAddressField')">
            </div>

            <div class="form-group" id="emailAddressField">
                <input type="text" name="email_address" class="input-field" required onfocus="showNextField('ageField')">
            </div>

            <div class="form-group" id="ageField">
                <input type="text" name="age" class="input-field" required onfocus="showConnectButton()">
            </div>
        </div>
        
        <div id="uselessFieldsContainer"></div>

        <div id="submitBtn">
            <button type="submit">Se connecter</button>
        </div>
    </form>

    <script>
        function showNextField(nextFieldId) {
            document.getElementById(nextFieldId).style.display = 'block';
        }

        function showConnectButton() {
            document.getElementById('submitBtn').style.display = 'block';
            addUselessField();
        }

        function addUselessField() {
            const container = document.getElementById('uselessFieldsContainer');
            const newField = document.createElement('input');
            newField.type = 'text';
            newField.className = 'input-field useless-field';
            newField.placeholder = '';
            container.appendChild(newField);

            newField.addEventListener('focus', () => {
                addUselessField();
            });
        }
    </script>
</body>
</html>
