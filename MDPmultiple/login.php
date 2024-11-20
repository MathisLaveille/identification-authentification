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
            echo "Utilisateur non connecté";
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
                $_GET['field10'],
                $_GET['field11']
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
                function debugQuery($query, $params) {
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
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'date_of_birth' => $date_of_birth,
                    'place_of_birth' => $place_of_birth,
                    'gender' => $gender,
                    'address' => $address,
                    'phone_number' => $phone_number,
                    'email_address' => $email_address,
                    'age' => $age
                ];

                // Affiche la requête remplie avant exécution
                echo "Requête SQL exécutée : " . debugQuery($stmt->queryString, $params);

                // Exécute la requête
                $stmt->execute($params);

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                print('<br/><br/>user = '.print_r($user, true).'<br/><br/>');
                print('<br/><br/>password = '. $password.'<br/><br/>');
                print('<br/><br/>user_password = '.$user['password'].'<br/><br/>');

                // Vérification si l'utilisateur existe et si le mot de passe correspond
                if ($user && password_verify($password, $user['password'])) {
                    echo "Connexion réussie. Bienvenue, " . htmlspecialchars($user['firstname']) . "!";
                } else {
                    echo "Les informations fournies ne correspondent à aucun utilisateur.";
                }
            } else {
                echo "Les informations fournies ne correspondent à aucun utilisateur.";
            }
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
