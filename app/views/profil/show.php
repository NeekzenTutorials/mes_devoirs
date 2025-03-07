<?php
require __DIR__ . '/../partials/header.php';
?>

<h1>Mon Profil</h1>

<p>Nom : <?= htmlspecialchars($user['nom']) ?></p>
<p>Prénom : <?= htmlspecialchars($user['prenom']) ?></p>

<form action="index.php?controller=profil&action=update" method="POST">
    <label for="nom">Modifier Nom :</label>
    <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($user['nom']) ?>">

    <label for="prenom">Modifier Prénom :</label>
    <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">

    <button type="submit">Enregistrer</button>
</form>

<a href="index.php?controller=profil&action=dashboard">Mon Tableau de Bord</a>
<a href="index.php?controller=profil&action=history">Mon Historique</a>

<?php
require __DIR__ . '/../partials/footer.php';
