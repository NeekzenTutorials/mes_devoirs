<?php
require __DIR__ . '/../partials/header.php';
?>

<link rel="stylesheet" href="\css\inscription.css">

<form class="form-container" action="index.php?controller=auth&action=doRegister" method="post">
    <label for="nom">Nom :</label><br>
    <input type="text" name="nom" id="nom" required><br><br>

    <label for="prenom">Prénom :</label><br>
    <input type="text" name="prenom" id="prenom" required><br><br>

    <label for="role">Rôle :</label><br>
    <select name="role" id="role" required>
        <option value="">-- Sélectionner un rôle --</option>
        <option value="enfant">Enfant</option>
        <option value="parent">Parent</option>
        <option value="enseignant">Enseignant</option>
    </select><br><br>

    <div id="classeDiv" style="display:none;">
        <label for="classe">Classe :</label><br>
        <select name="classe" id="classe">
            <option value="1">CP</option>
            <option value="2">CE1</option>
            <option value="3">CE2</option>
            <option value="4">CM1</option>
            <option value="5">CM2</option>
        </select><br><br>
    </div>

    <label for="mot_de_passe">Mot de passe :</label><br>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required><br><br>

    <input type="submit" value="S'inscrire">
</form>

<script>
    const roleSelect = document.getElementById('role');
    const classeDiv  = document.getElementById('classeDiv');

    roleSelect.addEventListener('change', () => {
        if (roleSelect.value === 'enfant') {
            classeDiv.style.display = 'block';
        } else {
            classeDiv.style.display = 'none';
        }
    });
</script>

<?php
require __DIR__ . '/../partials/footer.php';
