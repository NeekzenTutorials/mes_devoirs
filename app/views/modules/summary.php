<?php
$title = "Résumé - Exercice";
require __DIR__ . '/../partials/header.php';
?>

<!-- Ajout du fichier CSS -->
<link rel="stylesheet" href="/css/module_summary.css">

<main>
    <div class="module-container">
        <table class="table-container" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="module-cell" style="background-image:url('/images/NO.jpg');">
                    <center>
                        <div class="form-container">
                            <h1>Résumé de l'exercice</h1>
                            <h2>Bravo <?= htmlspecialchars($_SESSION['prenom']) ?> !</h2>
                            <h3>Vous avez obtenu <?= $_SESSION['nbBonneReponse'] ?> / <?= $_SESSION['nbMaxQuestions'] ?> bonnes réponses.</h3>

                            <table class="summary-table">
                                <tr>
                                    <th>Question</th>
                                    <th>Votre réponse</th>
                                    <th>Bonne réponse</th>
                                    <th>Temps (s)</th>
                                    <th>Résultat</th>
                                </tr>
                                <?php foreach ($_SESSION['questions'] as $question) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($question['question']) ?></td>
                                        <td><?= htmlspecialchars($question['user_answer'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($question['answer']) ?></td>
                                        <td><?= $question['duration'] ?? '-' ?></td>
                                        <td><?= isset($question['valid']) && $question['valid'] ? '✅' : '❌' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>

                            <br>
                            <a href="index.php?controller=module&action=startSession&type=<?= isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'addition' ?>" class="btn btn-primary">Nouvel exercice</a>
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

<?php require __DIR__ . '/../partials/footer.php'; ?>
