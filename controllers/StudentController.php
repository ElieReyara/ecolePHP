<?php
session_start();
require_once __DIR__ . '/../models/student.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../public/login.php");
    exit();
}

// Récupérer l'ID de l'étudiant depuis la session
$student_id = $_SESSION['user_id'];

// Créer un objet Student
$student = new Student($student_id);

// Récupérer les notes de l'étudiant
$grades = $student->getGrades();

// Récupérer la moyenne générale
$average = $student->getAverageGrade();

// Récupérer l'emploi du temps de l'étudiant
$schedule = $student->getSchedule();

// Passer les données à la vue
include '../views/students_views.php';
?>