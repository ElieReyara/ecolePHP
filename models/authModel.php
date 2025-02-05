<?php
require_once __DIR__.'/../config/database.php';

class Auth {
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

class authController {
    private $conn;

    // Constructeur avec la connexion à la base de données
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour connecter un utilisateur
    public function login($username, $password) {
        $user = new Auth($this->conn, $username, $password);

        $authenticated_user = $user->login();
        if ($authenticated_user) {
            session_start();
            $_SESSION['user_id'] = $authenticated_user['id'];
            $_SESSION['username'] = $authenticated_user['username'];
            $_SESSION['role'] = $authenticated_user['role'];
            return true;
        } else {
            return false; // Échec de la connexion
        }
    }

    // Méthode pour récupérer les informations de l'utilisateur connecté
    public function dashboard() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return $_SESSION; // Retourne les infos de session
        } else {
            return false; // L'utilisateur n'est pas connecté
        }
    }
}
?>
