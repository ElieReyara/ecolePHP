
<?php

class Subject {
    private $name;

    public function __construct($name = null) {
        if ($name) {
            $this->name = $name;
        }
    }

    // Méthode pour ajouter une matière dans la base de données
    public function addSubject() {
        require_once '../config/database.php'; // Connexion à la base de données
        
        $query = "INSERT INTO subjects (name) VALUES (?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $this->name);
            return $stmt->execute();
        } else {
            throw new Exception("Erreur lors de l'ajout de la matière.");
        }
    }

    // Méthode pour récupérer toutes les matières
    public function getAllSubjects() {
        require_once '../config/database.php'; // Connexion à la base de données
        
        $query = "SELECT id, name FROM subjects";
        $result = $conn->query($query);
        
        $subjects = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $subjects[] = $row;
            }
        }
        return $subjects;
    }
}
