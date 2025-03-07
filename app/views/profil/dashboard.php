<?php
require __DIR__ . '/../partials/header.php';
?>

<!-- Lien vers le nouveau CSS -->
<link rel="stylesheet" href="/css/dashboard.css">

<main>
    <div class="dashboard-container">
        <h1>Tableau de Bord</h1>

        <p>Nombre d'exercices réalisés : <?= $nbExercices ?></p>

        <div class="progress-container">
            <span class="progress-label">Pourcentage de réussite :</span>
            <div class="progress-bar-container">
                <!-- On affiche la barre -->
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <p id="pourcentage-text"><?= $pourcentage ?> %</p>
        </div>

        <p>Thème favori : <?= htmlspecialchars($themeFavori) ?></p>

        <a href="index.php?controller=profil&action=history">Voir l'historique des sessions</a>
        <a href="index.php?controller=profil&action=show">Retour au profil</a>
    </div>
</main>

<?php
require __DIR__ . '/../partials/footer.php';
?>

<script>
// Récupération du pourcentage depuis PHP
let pourcentage = <?= json_encode($pourcentage, JSON_HEX_TAG) ?>;

// On sélectionne l'élément .progress-bar
let progressBar = document.getElementById('progress-bar');

// Ajustement dynamique de la largeur de la barre
progressBar.style.width = pourcentage + '%';

// Eventuellement, on peut afficher le pourcentage au centre de la barre
progressBar.textContent = pourcentage + '%';
</script>
