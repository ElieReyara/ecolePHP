<!-- views/add_subject_form.php -->
<form method="POST" action="../controllers/userController.php?action=addSubject">
    <label for="subject_name">Nom de la Matière :</label>
    <input type="text" name="subject_name" required><br>

    <label for="teacher_id">Sélectionner un Enseignant :</label>
    <select name="teacher_id" required>
        <!-- Vous devez remplir cette liste avec les enseignants disponibles depuis la base de données -->
        <?php foreach ($teachers as $teacher): ?>
            <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Ajouter Matière</button>
</form>
