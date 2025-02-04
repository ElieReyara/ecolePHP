<?php
require_once '../models/subject.php'; // Assure-toi d'avoir une classe Subject

class SubjectController {

    // Méthode pour ajouter une matière
    public function addSubject($subject_name) {
        try {
            // Créer un objet Subject
            $subject = new Subject($subject_name);
            
            // Appeler la méthode pour ajouter la matière dans la base de données
            if ($subject->addSubject()) {
                echo "<div class='alert alert-success'>Matière ajoutée avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'ajout de la matière.</div>";
            }
        } catch (Exception $e) {
            // Gérer les erreurs
            echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
        }
    }

    // Méthode pour récupérer toutes les matières
    public function getAllSubjects() {
        try {
            $subject = new Subject();
            $subjects = $subject->getAllSubjects();
            
            if (!empty($subjects)) {
                echo "<ul class='list-group'>";
                foreach ($subjects as $item) {
                    echo "<li class='list-group-item'>" . htmlspecialchars($item['name']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<div class='alert alert-info'>Aucune matière trouvée.</div>";
            }
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
        }
    }
}
?>
