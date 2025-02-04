// controllers/ScheduleController.php
<?php
require_once '../models/schedule.php';

class ScheduleController {

    // Méthode pour afficher l'emploi du temps d'un enseignant
    public function getTeacherSchedule($teacher_id) {
        try {
            // Créer un objet Schedule
            $schedule = new Schedule(null, $teacher_id, null, null, null, null);
            $teacher_schedule = $schedule->getTeacherSchedule($teacher_id);
            
            if (!empty($teacher_schedule)) {
                foreach ($teacher_schedule as $item) {
                    echo "Matière: " . $item['subject_name'] . " | Jour: " . $item['day'] . " | Heure: " . $item['start_time'] . " - " . $item['end_time'] . "<br>";
                }
            } else {
                echo "Aucun emploi du temps trouvé pour cet enseignant.";
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Méthode pour afficher l'emploi du temps d'un étudiant
    public function getStudentSchedule($class) {
        try {
            $schedule = new Schedule(null, null, $class, null, null, null);
            $student_schedule = $schedule->getStudentSchedule($class);
            
            if (!empty($student_schedule)) {
                foreach ($student_schedule as $item) {
                    echo "Matière: " . $item['subject'] . " | Jour: " . $item['day'] . " | Heure: " . $item['start_time'] . " - " . $item['end_time'] . "<br>";
                }
            } else {
                echo "Aucun emploi du temps trouvé pour cette classe.";
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Méthode pour ajouter un emploi du temps
    public function addSchedule($subject_id, $teacher_id, $class, $day, $start_time, $end_time) {
        try {
            $schedule = new Schedule($subject_id, $teacher_id, $class, $day, $start_time, $end_time);
            if ($schedule->addSchedule()) {
                echo "Emploi du temps ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout de l'emploi du temps.";
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
?>
