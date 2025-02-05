<?php
// controllers/UserController.php : Contrôleur des utilisateurs

require_once __DIR__.'/../config/database.php'; // Assurez-vous que la connexion est bien établie
require_once __DIR__.'/../models/user.php';

// Vérification des requêtes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $controller = new authController($conn); // Passer la connexion

    if ($action == 'signup') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];

        echo $controller->signup($username, $password, $role, $first_name, $last_name);

    } elseif ($action == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($controller->login($username, $password)) {
            header("Location: ../public/dashboard.php");
        } else {
            echo "Identifiants incorrects.";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du rôle de l'utilisateur
    $role = $_POST['role'];

    // Ajout de l'utilisateur (nom d'utilisateur, mot de passe, rôle)
    if ($role === 'admin') {
        // Si l'administrateur ajoute une matière
        if (isset($_POST['new_subject']) && !empty($_POST['new_subject'])) {
            $newSubject = mysqli_real_escape_string($conn, $_POST['new_subject']);
            
            // Insertion de la nouvelle matière dans la table "subjects"
            $query = "INSERT INTO subjects (name) VALUES ('$newSubject')";
            if ($conn->query($query) === TRUE) {
                echo "Nouvelle matière ajoutée avec succès !";
            } else {
                echo "Erreur lors de l'ajout de la matière : " . $conn->error;
            }
        }
    }

    // Autres actions pour l'inscription de l'utilisateur
    // ...
}


?>
