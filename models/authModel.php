<?php
require_once __DIR__.'/../config/database.php';

class Auth {
    private $id;
    private $username;
    private $password;
    private $role;
    private $conn;

    // Constructeur
    public function __construct($conn, $id = 0, $password = '', $username = '', $role = '') {
        $this->conn = $conn;
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    // Méthode pour se connecter (vérification du mot de passe)
    public function login() {
        try{
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
           echo "Erreur: " . $e->getMessage();
        }

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($this->password, $user['password'])) {
                return $user;
            }
        }else{
            return false;
        }
    }

    public function createSession($id, $password) {
        $authenticated_user = $this->login();
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

    // Getter pour le rôle
    public function getRole() {
        return $this->role;
    }

    // Getter pour l'ID de l'utilisateur
    public function getId() {
        return $this->id;
    }
}
// $user1 = new Auth($conn, 4, "Bob", "qw");
// $test = $user1->login();
?>
