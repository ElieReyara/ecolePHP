<?php
require_once __DIR__ . '/../config/database.php';

class Teacher {
    private $conn;
    private $id;

    public function __construct($teacher_id) {
        $this->conn = Database::getConnection();
        $this->id = $teacher_id;
    }

    public function getSubjects() {
        $stmt = $this->conn->prepare("SELECT id, name FROM subjects WHERE teacher_id = ?");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentsBySubject($subject_id) {
        $stmt = $this->conn->prepare("
            SELECT students.id, students.first_name, students.last_name 
            FROM students 
            JOIN grades ON students.id = grades.student_id 
            WHERE grades.subject_id = ?
        ");
        $stmt->execute([$subject_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addGrade($student_id, $subject_id, $grade) {
        $stmt = $this->conn->prepare("INSERT INTO grades (student_id, subject_id, teacher_id, grade, date) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$student_id, $subject_id, $this->id, $grade]);
    }

    public function deleteGrade($grade_id) {
        $stmt = $this->conn->prepare("DELETE FROM grades WHERE id = ?");
        return $stmt->execute([$grade_id]);
    }

    public function addSchedule($subject_id, $class, $day, $start_time, $end_time) {
        $stmt = $this->conn->prepare("INSERT INTO schedules (subject_id, teacher_id, class, day, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$subject_id, $this->id, $class, $day, $start_time, $end_time]);
    }

    public function deleteSchedule($schedule_id) {
        $stmt = $this->conn->prepare("DELETE FROM schedules WHERE id = ?");
        return $stmt->execute([$schedule_id]);
    }
}

public function addSubject($subject_name) {
    // Vérifier si l'enseignant existe dans la table teachers
    $teacher_check_stmt = $this->conn->prepare("SELECT COUNT(*) FROM teachers WHERE id = ?");
    $teacher_check_stmt->execute([$this->id]);
    $teacher_exists = $teacher_check_stmt->fetchColumn() > 0;

    if ($teacher_exists) {
        // Si l'enseignant existe, insérer la matière
        $stmt = $this->conn->prepare("INSERT INTO subjects (name, teacher_id) VALUES (?, ?)");
        return $stmt->execute([$subject_name, $this->id]);
    } else {
        // Si l'enseignant n'existe pas, retourner une erreur
        return false; // ou une gestion d'erreur spécifique
    }
}

    

?>


