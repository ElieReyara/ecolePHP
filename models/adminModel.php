<?php

class Admin{


    // Méthode pour inscrire un nouvel utilisateur
    public function signup($username, $password, $role, $first_name, $last_name) {
        $user = new User($this->conn, $username, $password, $role);

        // Ajouter l'utilisateur et le lier à la bonne table
        $result = $user->addUser($username, $password, $role, $first_name, $last_name);

        if ($result === true) {
            return "Inscription réussie!";
        } else {
            return "Échec de l'inscription : " . $result;
        }
    }

    // Méthode pour ajouter un utilisateur et l'affecter à students ou teachers
    public function addUser($username, $password, $role, $first_name, $last_name) {
        try {
            // Vérifier si le nom d'utilisateur existe déjà
            $checkStmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                return "Erreur : Ce nom d'utilisateur existe déjà.";
            }

            // Hasher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Début de transaction
            $this->conn->begin_transaction();

            // Étape 1 : Insérer dans la table users
            $query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $username, $hashed_password, $role);
            $stmt->execute();

            // Récupérer l'ID de l'utilisateur ajouté
            $user_id = $this->conn->insert_id;

            // Étape 2 : Ajouter dans la table correspondante
            if ($role === 'student') {
                $query = "INSERT INTO students (user_id, first_name, last_name) VALUES (?, ?, ?)";
            } elseif ($role === 'teacher') {
                $query = "INSERT INTO teachers (user_id, first_name, last_name) VALUES (?, ?, ?)";
            } else {
                throw new Exception("Rôle invalide.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iss", $user_id, $first_name, $last_name);
            $stmt->execute();

            // Validation de la transaction
            $this->conn->commit();
            return "Utilisateur ajouté avec succès !";

        } catch (Exception $e) {
            // Annulation en cas d'erreur
            $this->conn->rollback();
            return "Erreur : " . $e->getMessage();
        }
    }
}

?>