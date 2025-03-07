<?php
require __DIR__ . '/../partials/header.php';
?>

<h1>Tableau de Bord</h1>

<p>Nombre d'exercices réalisés : <?= $nbExercices ?></p>
<p>Pourcentage de réussite : <?= $pourcentage ?> %</p>
<p>Thème favori : <?= htmlspecialchars($themeFavori) ?></p>

<a href="index.php?controller=profil&action=history">Voir l'historique des sessions</a>
<a href="index.php?controller=profil&action=show">Retour au profil</a>

<?php
require __DIR__ . '/../partials/footer.php';
