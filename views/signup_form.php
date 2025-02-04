<?php
session_start();
// Vérifier les permissions si nécessaire
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Utilisateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 250px; 
            background-color: #343a40; 
            padding-top: 20px; 
        }
        .sidebar a { 
            color: white; 
            padding: 10px 15px; 
            text-decoration: none; 
            display: block; 
        }
        .sidebar a:hover { 
            background-color: #495057; 
        }
        .content { 
            margin-left: 270px; 
            padding: 20px; 
        }
        .profile-info { 
            display: flex; 
            align-items: center; 
            margin-bottom: 30px; 
        }
        .profile-info i { 
            font-size: 30px; 
            margin-right: 10px; 
            color: #007bff; 
        }
        .form-card { 
            max-width: 600px; 
            margin: 0 auto; 
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .input-group-text {
            background-color: #e9f5ff;
            border-color: #dee2e6;
        }
    </style>
</head>
<body class="bg-light">

<!-- Barre latérale -->
<div class="sidebar">
    <h3 class="text-white text-center">Gestion Utilisateurs</h3>
    <a href="../public/dashboard.php"><i class="fas fa-home me-2"></i>Accueil</a>
    <a href="user.php"><i class="fas fa-users me-2"></i>Liste Utilisateurs</a>
    <a href="login_form.php" class="btn btn-danger mt-3 mx-2"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a>
</div>

<!-- Contenu principal -->
<div class="content">
    <div class="profile-info">
        <i class="fas fa-user-plus"></i>
        <h2>Inscription Nouvel Utilisateur</h2>
    </div>

    <div class="card form-card border-primary">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>Formulaire d'inscription</h4>
        </div>
        
        <div class="card-body p-4">
            <form method="POST" action="../controllers/userController.php?action=signup">
                <!-- Champ Nom d'utilisateur -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Identifiant</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
                    </div>
                </div>

                <!-- Champ Mot de passe -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                    </div>
                </div>

                <!-- Sélecteur de Rôle -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Rôle Utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                        <select name="role" class="form-select" required onchange="toggleFields()">
                            <option value="admin">Administrateur</option>
                            <option value="teacher">Enseignant</option>
                            <option value="student">Étudiant</option>
                        </select>
                    </div>
                </div>

                <!-- Champs supplémentaires -->
                <div id="extra-fields" class="bg-light p-3 rounded-3 mb-4" style="display: none;">
                    <!-- Prénom -->
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" name="first_name" class="form-control" placeholder="Prénom">
                        </div>
                    </div>

                    <!-- Nom -->
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                            <input type="text" name="last_name" class="form-control" placeholder="Nom de famille">
                        </div>
                    </div>

                    <!-- Matières Enseignées -->
                    <div id="subjects-field" style="display: none;">
                        <label class="form-label">Matières Associées</label>
                        <select name="subjects[]" class="form-select" multiple>
                            <?php
                            $query = "SELECT id, name FROM subjects";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Ajout Matière Admin -->
                    <div id="admin-subjects-field" class="mt-3" style="display: none;">
                        <label class="form-label">Nouvelle Matière</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-plus-circle"></i></span>
                            <input type="text" name="new_subject" class="form-control" placeholder="Créer une nouvelle matière">
                        </div>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleFields() {
    const role = document.querySelector("select[name='role']").value;
    const extraFields = document.getElementById("extra-fields");
    const subjectsField = document.getElementById("subjects-field");
    const adminSubjectsField = document.getElementById("admin-subjects-field");

    // Gestion de la visibilité
    if (role === 'teacher' || role === 'student') {
        extraFields.style.display = 'block';
        subjectsField.style.display = role === 'teacher' ? 'block' : 'none';
        adminSubjectsField.style.display = 'none';
    } else if (role === 'admin') {
        extraFields.style.display = 'block';
        subjectsField.style.display = 'none';
        adminSubjectsField.style.display = 'block';
    } else {
        extraFields.style.display = 'none';
    }
}
</script>

</body>
</html>