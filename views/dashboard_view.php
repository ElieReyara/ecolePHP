<?php
require_once  __DIR__.'../controllers/userController.php';

// Vérification que l'utilisateur est un admin
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$controller = new UserController();
$users = $controller->listUsers(); // Liste des utilisateurs
?>

<h1>Dashboard Admin</h1>

<h2>Ajouter un enseignant</h2>
<form method="POST" action="add_teacher.php">
    <input type="text" name="first_name" placeholder="Prénom" required>
    <input type="text" name="last_name" placeholder="Nom" required>
    <input type="number" name="user_id" placeholder="ID de l'utilisateur" required>
    <button type="submit">Ajouter un enseignant</button>
</form>

<h2>Ajouter un étudiant</h2>
<form method="POST" action="add_student.php">
    <input type="text" name="first_name" placeholder="Prénom" required>
    <input type="text" name="last_name" placeholder="Nom" required>
    <input type="number" name="user_id" placeholder="ID de l'utilisateur" required>
    <input type="date" name="birthdate" required>
    <button type="submit">Ajouter un étudiant</button>
</form>

<h2>Liste des utilisateurs</h2>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user['username'] ?> (<?= $user['role'] ?>)</li>
    <?php endforeach; ?>
</ul>
