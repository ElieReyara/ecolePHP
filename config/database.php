 <?php
 // config/database.php

$servername = "localhost:3307";  // Remplace par ton hôte, par exemple localhost
$username = "root";         // Ton nom d'utilisateur MySQL
$password = "";             // Ton mot de passe MySQL (vide par défaut sur XAMPP)
$dbname = "school_management"; // Le nom de ta base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
