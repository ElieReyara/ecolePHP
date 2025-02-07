
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

// Redirection selon le rôle
if ($_SESSION['role'] === 'admin') {
    header("Location: ../views/admin/signup_form.php");
    exit();
} elseif ($_SESSION['role'] === 'teacher') {
    header("Location: ../views/teachers_view.php");
    exit();
} elseif ($_SESSION['role'] === 'student') {
    header("Location: ../views/students_views.php");
    exit();
}
?>


$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Gestion Scolaire</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link text-white">Bienvenue, <?php echo $username; ?> !</span>
                </li>
                <li class="nav-item">
                    <a href="dashboard.php" class="btn btn-danger">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h2 class="card-title">Bienvenue, <?php echo $username; ?> !</h2>
                    <p class="card-text">Accédez rapidement aux fonctionnalités de gestion de l'école.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections avec des raccourcis -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Étudiants</h5>
                    <p class="card-text">Gérez les étudiants inscrits.</p>
                    <a href="etudiants.php" class="btn btn-primary">Voir les étudiants</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Enseignants</h5>
                    <p class="card-text">Consultez la liste des enseignants.</p>
                    <a href="enseignants.php" class="btn btn-primary">Voir les enseignants</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Matières</h5>
                    <p class="card-text">Gérez les matières enseignées.</p>
                    <a href="matieres.php" class="btn btn-primary">Voir les matières</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
