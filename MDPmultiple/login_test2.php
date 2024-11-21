<?php
$host = 'localhost';
$dbname = 'identificationauthentification';
$username = 'root';
$password = 'root';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['field11'])) {

        if ($_GET['field11'] != "") {
            echo "Les informations fournies ne correspondent à aucun utilisateur.";
        } else {
            // Vérification que toutes les données sont présentes dans l'URL avec $_GET
            if (isset(
                $_GET['field1'],
                $_GET['field2'],
                $_GET['field3'],
                $_GET['field4'],
                $_GET['field5'],
                $_GET['field6'],
                $_GET['field7'],
                $_GET['field8'],
                $_GET['field9'],
                $_GET['field10']
            )) {

                // Récupération des données envoyées via GET
                $firstname = $_GET['field1'];
                $lastname = $_GET['field2'];
                $password = $_GET['field3'];
                $date_of_birth = $_GET['field4'];
                $place_of_birth = $_GET['field5'];
                $gender = $_GET['field6'];
                $address = $_GET['field7'];
                $phone_number = $_GET['field8'];
                $email_address = $_GET['field9'];
                $age = $_GET['field10'];

                // Fonction pour déboguer la requête SQL
                function debugQuery($query, $params)
                {
                    foreach ($params as $key => $value) {
                        $escapedValue = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
                        $query = str_replace(":$key", $escapedValue, $query);
                    }
                    return $query;
                }

                // Préparation de la requête
                $stmt = $pdo->prepare("SELECT * FROM users 
                                        WHERE firstname = :firstname 
                                        AND lastname = :lastname 
                                        AND password = :password
                                        AND date_of_birth = :date_of_birth 
                                        AND place_of_birth = :place_of_birth
                                        AND gender = :gender
                                        AND address = :address
                                        AND phone_number = :phone_number
                                        AND email_address = :email_address
                                        AND age = :age");

                // Paramètres de la requête
                $params = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'password' => $password,
                    'date_of_birth' => $date_of_birth,
                    'place_of_birth' => $place_of_birth,
                    'gender' => $gender,
                    'address' => $address,
                    'phone_number' => $phone_number,
                    'email_address' => $email_address,
                    'age' => $age
                ];

                // Exécute la requête
                $stmt->execute($params);

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user == true) {
                    print_r("connexion réussie. Bienvenue, " . htmlspecialchars($user['firstname']) . "!");
                } else {
                    print("Les informations fournies ne correspondent à aucun utilisateur.");
                }
            } else {
                print("Les informations fournies ne correspondent à aucun utilisateur.");
            }
        }
    }else {
        print("Les informations fournies ne correspondent à aucun utilisateur.");
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
    <title>Page de Connexion Dynamique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }

        input[type="text"] {
            margin: 10px 0;
            padding: 8px;
            width: 200px;
        }

        button {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Formulaire de Connexion</h2>
        <form id="loginForm">
            <div id="formFields">
                <!-- Le premier champ de formulaire est ajouté ici -->
                <input type="text" name="field1" placeholder="Entrez votre identifiant" onclick="addField(event)">
            </div>
            <br>
            <button type="submit">Se Connecter</button>
        </form>
    </div>

    <script>
        let fieldCount = 1; // Compteur pour nommer les champs de manière unique

        // Fonction pour ajouter un champ uniquement lorsque l'on clique sur le dernier champ ajouté
        function addField(event) {
            const formFields = document.getElementById('formFields');
            const inputs = formFields.getElementsByTagName('input'); // Récupère tous les champs
            const lastInput = inputs[inputs.length - 1]; // Le dernier champ

            // Vérifie si le champ cliqué est le dernier, sinon rien ne se passe
            if (event.target !== lastInput) return;

            fieldCount++; // Incrémente le compteur pour générer un nom unique

            // Crée un nouvel input et lui attribue un nom unique
            const newField = document.createElement('input');
            newField.type = 'text';
            newField.name = 'field' + fieldCount; // Donne un nom unique pour chaque champ
            newField.placeholder = 'Entrez votre identifiant : ' + fieldCount;
            newField.onclick = addField; // Ajoute un nouvel input à chaque clic

            // Ajoute le nouveau champ à la fin
            formFields.appendChild(newField);
        }
    </script>

</body>

</html>