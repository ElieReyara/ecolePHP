// models/Schedule.php
<?php
require_once '../config/database.php';

class Schedule {
    private $conn;
    private $subject_id;
    private $teacher_id;
    private $class;
    private $day;
    private $start_time;
    private $end_time;

    public function __construct($subject_id, $teacher_id, $class, $day, $start_time, $end_time) {
        $this->conn = Database::getConnection();
        $this->subject_id = $subject_id;
        $this->teacher_id = $teacher_id;
        $this->class = $class;
        $this->day = $day;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    // Méthode pour ajouter un emploi du temps
    public function addSchedule() {
        $stmt = $this->conn->prepare("INSERT INTO schedules (subject_id, teacher_id, class, day, start_time, end_time) 
                                     VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->subject_id, 
            $this->teacher_id, 
            $this->class, 
            $this->day, 
            $this->start_time, 
            $this->end_time
        ]);
    }

    // Méthode pour récupérer les emplois du temps d'un enseignant
    public function getTeacherSchedule($teacher_id) {
        $stmt = $this->conn->prepare("SELECT * FROM schedules 
                                     WHERE teacher_id = ? 
                                     ORDER BY day, start_time");
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer l'emploi du temps d'un étudiant
    public function getStudentSchedule($class) {
        $stmt = $this->conn->prepare("SELECT subjects.name AS subject, schedules.day, schedules.start_time, schedules.end_time 
                                     FROM schedules
                                     JOIN subjects ON schedules.subject_id = subjects.id
                                     WHERE schedules.class = ?
                                     ORDER BY schedules.day, schedules.start_time");
        $stmt->execute([$class]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>