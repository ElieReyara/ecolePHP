<?php
// TeacherController.php

public function addSubject() {
    session_start();
    
    // Vérifier si l'enseignant est connecté (user_id dans la session)
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        echo "Utilisateur non connecté.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validation des données du formulaire
        if (empty($_POST['subject_name'])) {
            echo "Le nom de la matière est requis.";
            return;
        }
        
        $subject_name = trim($_POST['subject_name']);
        
        // Création d'un objet Teacher avec l'ID de l'utilisateur connecté
        $teacher = new Teacher($_SESSION['user_id']);
        
        // Appeler la méthode addSubject du modèle Teacher pour ajouter une matière
        if ($teacher->addSubject($subject_name)) {
            // Si l'ajout réussit, rediriger vers la page du tableau de bord
            header("Location: ../views/teachers_view.php");
            exit();
        } else {
            // Si l'ajout échoue, afficher un message d'erreur plus détaillé
            echo "Échec de l'ajout de la matière. Vérifiez que l'enseignant existe et essayez à nouveau.";
        }
    } else {
        // Si ce n'est pas une requête POST, afficher le formulaire
        require_once __DIR__ . '/../views/teachers_view.php';
    }
}
?>
