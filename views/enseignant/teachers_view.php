<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

// Récupérer toutes les matières
$query_subjects = "SELECT id, name FROM subjects";
$result_subjects = $conn->query($query_subjects);
$subjects = [];
if ($result_subjects) {
    while ($row = $result_subjects->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Enseignant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar { height: 100vh; position: fixed; top: 0; left: 0; width: 250px; background-color: #343a40; padding-top: 20px; }
        .sidebar a { color: white; padding: 10px 15px; text-decoration: none; display: block; }
        .sidebar a:hover { background-color: #495057; }
        .content { margin-left: 270px; padding: 20px; }
        .section { display: none; }
        .profile-info { display: flex; align-items: center; margin-bottom: 30px; }
        .profile-info i { font-size: 30px; margin-right: 10px; color: #007bff; }
        .form-card { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body class="bg-light">

<!-- Barre latérale -->
<div class="sidebar">
    <h3 class="text-white text-center"><?php echo $_SESSION['username']; ?></h3>
    <a href="javascript:void(0)" onclick="showSection('add-subject')">Ajouter Matière</a>
    <a href="javascript:void(0)" onclick="showSection('add-grade')">Ajouter Note</a>
    <a href="javascript:void(0)" onclick="showSection('add-schedule')">Ajouter Emploi du Temps</a>
    <a href="../public/dashboard.php" class="btn btn-danger mt-3 mx-2">Déconnexion</a>
</div>

<!-- Contenu principal -->
<div class="content">
    <div class="profile-info">
        <i class="fas fa-chalkboard-teacher"></i>
        <h2>Bienvenue, <?php echo $_SESSION['username']; ?> !</h2>
    </div>

    <!-- Section Ajout Matière -->
    <div id="add-subject" class="section">
        <div class="card form-card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-book"></i> Nouvelle Matière</h4>
                <form action="../controllers/subjectController.php?action=addSubject" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nom de la matière</label>
                        <input type="text" name="subject_name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Section Ajout Note -->
    <div id="add-grade" class="section">
        <div class="card form-card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-marker"></i> Ajouter une Note</h4>
                <form action="../controllers/gradeController.php?action=addGrade" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Matière</label>
                        <select name="subject_id" class="form-select" required>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ID Étudiant</label>
                        <input type="number" name="student_id" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <input type="number" step="0.1" name="grade" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Section Ajout Emploi du Temps -->
    <div id="add-schedule" class="section">
        <div class="card form-card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-calendar-alt"></i> Planifier un Cours</h4>
                <form action="../controllers/scheduleController.php?action=addSchedule" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Matière</label>
                            <select name="subject_id" class="form-select" required>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jour</label>
                            <select name="day" class="form-select" required>
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure de début</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure de fin</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Classe/Groupe</label>
                        <input type="text" name="class" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Planifier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSection(sectionId) {
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }

    // Afficher la première section par défaut
    window.onload = () => showSection('add-subject');
</script>

</body>
</html>