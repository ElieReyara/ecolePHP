<?php

// Inclure le fichier de connexion à la base de données
require_once '../confige/database.php';
class NoteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Initialisation de la base de données
    }

    // Récupérer l'etudiant_id à partir du user_id
    public function getStudentIdByUserId($userId) {
        $query = "SELECT id FROM etudiant WHERE user_id = :userId"; // On suppose que user_id est la clé étrangère
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Retourner l'etudiant_id si trouvé
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        return $student ? $student['id'] : null; // Retourne null si l'étudiant n'est pas trouvé
    }

    // Récupérer les notes de l'étudiant par son ID
    public function getNotesByStudentId($studentId) {
        $query = "SELECT 
                      n.note, 
                      m.nom AS matiere, 
                      n.date_attribution 
                  FROM note n
                  JOIN matiere m ON n.matiere_id = m.id
                  WHERE n.etudiant_id = :studentId"; // Vérifie le champ `etudiant_id` dans `note`

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer la moyenne des notes de l'étudiant
    public function getAverageByStudentId($studentId) {
        $notes = $this->getNotesByStudentId($studentId);

        if (count($notes) === 0) {
            return 0; // Aucun résultat, retourne 0
        }

        $sum = 0;
        foreach ($notes as $note) {
            $sum += $note['note']; // Additionner les notes
        }

        return $sum / count($notes); // Calculer la moyenne
    }
}
?>