<?php
session_start();

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si l'utilisateur est connecté et a le rôle "student"
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php'; // Connexion à la base de données

// Vérifier la connexion
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$student_id = $_SESSION['user_id'];  // ID de l'étudiant connecté

// Récupérer les notes de l'étudiant
$query_grades = "SELECT subjects.name AS subject, grades.grade
                 FROM grades
                 JOIN subjects ON grades.subject_id = subjects.id
                 WHERE grades.student_id = ?";
$stmt = $conn->prepare($query_grades);
if (!$stmt) {
    die("Erreur SQL : " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result_grades = $stmt->get_result();

$grades = [];
while ($row = $result_grades->fetch_assoc()) {
    $grades[] = $row;
}

// Récupérer l'emploi du temps des matières de l'étudiant
$query_schedule = "SELECT subjects.name AS subject, schedules.day, schedules.start_time, schedules.end_time
                   FROM schedules
                   JOIN subjects ON schedules.subject_id = subjects.id
                   JOIN grades ON grades.subject_id = subjects.id
                   WHERE grades.student_id = ?";  
$stmt = $conn->prepare($query_schedule);
if (!$stmt) {
    die("Erreur SQL : " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result_schedule = $stmt->get_result();

$schedule = [];
while ($row = $result_schedule->fetch_assoc()) {
    $schedule[] = $row;
}

// Calculer la moyenne générale de l'étudiant
$query_average = "SELECT AVG(grades.grade) AS average FROM grades
                  WHERE grades.student_id = ?";
$stmt = $conn->prepare($query_average);
if (!$stmt) {
    die("Erreur SQL : " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result_average = $stmt->get_result();
$average = $result_average->fetch_assoc()['average'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Étudiant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar { height: 100vh; position: fixed; top: 0; left: 0; width: 250px; background-color: #343a40; padding-top: 20px; }
        .sidebar a { color: white; padding: 10px 15px; text-decoration: none; display: block; }
        .sidebar a:hover { background-color: #495057; }
        .content { margin-left: 270px; padding: 20px; }
        .profile-info { display: flex; align-items: center; }
        .profile-info img { width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; }
        .profile-info i { font-size: 30px; margin-right: 10px; color: #007bff; }
    </style>
</head>
<body class="bg-light">

<!-- Barre latérale -->
<div class="sidebar">
    <h3 class="text-white text-center"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
    <a href="javascript:void(0)" onclick="showSection('notes')">Mes Notes</a>
    <a href="javascript:void(0)" onclick="showSection('average')">Moyenne Générale</a>
    <a href="javascript:void(0)" onclick="showSection('schedule')">Emploi du Temps</a>
    <a href="../public/dashboard.php" class="btn btn-danger">Déconnexion</a>
</div>

<!-- Contenu principal -->
<div class="content">
    <div class="profile-info">
        <i class="fas fa-user-circle"></i> 
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>
    </div>

    <!-- Affichage des notes -->
    <div id="notes" class="section card mt-4">
        <div class="card-body">
            <h4 class="card-title">Mes Notes</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['subject']); ?></td>
                            <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Affichage de la moyenne -->
    <div id="average" class="section alert alert-info mt-3">
        <h5>Moyenne Générale : <?php echo number_format($average, 2); ?></h5>
    </div>

    <!-- Affichage de l'emploi du temps -->
    <div id="schedule" class="section card mt-4">
        <div class="card-body">
            <h4 class="card-title">Emploi du Temps</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Jour</th>
                        <th>Début</th>
                        <th>Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedule as $session): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($session['subject']); ?></td>
                            <td><?php echo htmlspecialchars($session['day']); ?></td>
                            <td><?php echo htmlspecialchars($session['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($session['end_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showSection(sectionId) {
        var sections = document.querySelectorAll('.section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }

    window.onload = function() {
        showSection('notes');
    };
</script>

</body>
</html>
