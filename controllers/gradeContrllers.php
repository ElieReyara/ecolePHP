<?php
require_once '../models/grade.php';
require_once '../models/student.php';  // Si tu veux récupérer l'étudiant par exemple

class GradeController {

    // Méthode pour ajouter une note
    public function addGrade($student_id, $subject_id, $grade_value) {
        try {
            // Créer un objet Grade avec les données
            $grade = new Grade($student_id, $subject_id, $grade_value);
            
            // Appeler la méthode pour ajouter la note dans la base de données
            if ($grade->addGrade()) {
                echo "Note ajoutée avec succès.";
            } else {
                echo "Erreur lors de l'ajout de la note.";
            }
        } catch (Exception $e) {
            // Gérer les erreurs
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Méthode pour afficher les notes d'un étudiant
    public function getStudentGrades($student_id) {
        $student = new Student($student_id);
        $grades = $student->getGrades(); // Méthode pour récupérer les notes

        if (!empty($grades)) {
            foreach ($grades as $grade) {
                echo "Matière: " . $grade['subject'] . " | Note: " . $grade['grade'] . "<br>";
            }
        } else {
            echo "Aucune note trouvée pour cet étudiant.";
        }
    }
}
?>
