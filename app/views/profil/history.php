<?php
require __DIR__ . '/../partials/header.php';
?>

<!-- Lien vers le nouveau CSS -->
<link rel="stylesheet" href="/css/history.css">

<main>
    <div class="history-container">
        <h1>Mon Historique</h1>

        <!-- Tableau stylisé -->
        <table class="history-table">
            <tr>
                <th>Session</th>
                <th>Date</th>
                <th>Nombre de questions</th>
                <th>Action</th>
            </tr>
            <?php foreach ($sessions as $s) : ?>
                <tr>
                    <td>#<?= $s['id_session'] ?></td>
                    <td><?= $s['date_creation'] ?></td>
                    <td><?= $s['nb_questions'] ?></td>
                    <td>
                        <a href="index.php?controller=profil&action=sessionDetail&id_session=<?= $s['id_session'] ?>">
                            Voir détail
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="index.php?controller=profil&action=dashboard">Retour au tableau de bord</a>
    </div>
</main>

<?php
require __DIR__ . '/../partials/footer.php';
?>
