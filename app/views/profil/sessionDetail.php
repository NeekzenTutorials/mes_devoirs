<?php
require __DIR__ . '/../partials/header.php';
?>

<h1>Détails de la Session #<?= htmlspecialchars($_GET['id_session']) ?></h1>

<table border="1">
    <tr>
        <th>Question #</th>
        <th>Enoncé</th>
        <th>Votre réponse</th>
        <th>Bonne réponse</th>
        <th>Résultat</th>
        <th>Temps (s)</th>
    </tr>
    <?php foreach ($reponses as $r) : ?>
        <tr>
            <td><?= $r['num_question'] ?></td>
            <td><?= htmlspecialchars($r['enonce']) ?></td>
            <td><?= htmlspecialchars($r['reponse']) ?></td>
            <td><?= htmlspecialchars($r['resultat']) ?></td>
            <td><?= $r['valide'] ? '✅' : '❌' ?></td>
            <td><?= $r['duree'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="index.php?controller=profil&action=history">Retour à l'historique</a>
<?php
require __DIR__ . '/../partials/footer.php';
