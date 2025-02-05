<?php
require_once __DIR__.'/../config/database.php';

class User {
    private $id;
    private $username;
    private $password;
    private $role;
    private $conn;

    // Constructeur
    public function __construct($conn, $username = '', $password = '', $role = '') {
        $this->conn = $conn;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    // Méthode pour se connecter (vérification du mot de passe)
    public function login() {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($this->password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // Getter pour le rôle
    public function getRole() {
        return $this->role;
    }

    // Getter pour l'ID de l'utilisateur
    public function getId() {
        return $this->id;
    }
}
?>
