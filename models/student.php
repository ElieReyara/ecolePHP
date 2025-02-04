<?php
require_once _DIR_ . '/../config/database.php';

class Student {
    private $conn;
    private $id;

    public function __construct($student_id) {
        $this->conn = Database::getConnection();
        $this->id = $student_id;
    }

    public function getGrades() {
        $stmt = $this->conn->prepare("
            SELECT subjects.name AS subject, grades.grade 
            FROM grades
            JOIN subjects ON grades.subject_id = subjects.id
            WHERE grades.student_id = ?
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSchedule() {
        $stmt = $this->conn->prepare("
            SELECT subjects.name AS subject, schedules.day, schedules.start_time, schedules.end_time 
            FROM schedules
            JOIN subjects ON schedules.subject_id = subjects.id
            WHERE schedules.class = (
                SELECT class FROM students WHERE id = ?
            )
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageGrade() {
        $stmt = $this->conn->prepare("
            SELECT AVG(grade) AS average FROM grades WHERE student_id = ?
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['average'];
    }
}


    

?>