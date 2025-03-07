<?php
$title = "Résultat - " . htmlspecialchars($_GET['type']);
require __DIR__ . '/../partials/header.php';
?>

<!-- Ajout du fichier CSS -->
<link rel="stylesheet" href="/css/module_result.css">

<main>
    <div class="module-container">
        <table class="table-container" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="module-cell" style="background-image:url('/images/NO.jpg');">
                    <center>
                        <div class="form-container">
                            <h1>Résultat</h1>
                            <h2>Voici votre résultat :</h2>

                            <p><strong>Votre réponse :</strong> <?= htmlspecialchars($reponseUtilisateur) ?></p>
                            <p><strong>Bonne réponse :</strong> <?= htmlspecialchars($reponseAttendue) ?></p>
                            <p><strong>Temps écoulé :</strong> <?= $duree ?> secondes</p>

                            <?php if ($valide): ?>
                                <p style="color: green;"><strong>✅ Bonne réponse !</strong></p>
                            <?php else: ?>
                                <p style="color: red;"><strong>❌ Mauvaise réponse.</strong></p>
                            <?php endif; ?>

                            <br>
                            <a href="index.php?controller=module&action=question&type=<?= htmlspecialchars($_GET['type']) ?>" class="btn btn-primary">Question suivante</a>
                            <br><br>
                            <a href="index.php?controller=home&action=index" class="btn btn-secondary">Retour à l'accueil</a>
                        </div>
                    </center>
                </td>
                <td class="side-cell" style="background-image:url('/images/NE.jpg');"></td>
            </tr>
            <tr>
                <td class="module-cell" style="background-image:url('/images/SO.jpg');"></td>
                <td class="side-cell" style="background-image:url('/images/SE.jpg');"></td>
            </tr>
        </table>
    </div>
</main>

<?php
require __DIR__ . '/../partials/footer.php';
?>
