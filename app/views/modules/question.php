<?php
$title = "Question - " . htmlspecialchars($_GET['type']);
require __DIR__ . '/../partials/header.php';
?>

<!-- Ajout du fichier CSS -->
<link rel="stylesheet" href="/css/module_question.css">

<main>
    <div class="module-container">
        <table class="table-container" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="module-cell" style="background-image:url('/images/NO.jpg');">
                    <center>
                        <div class="form-container">
                            <h1>Question <?= $_SESSION['nbQuestion'] ?></h1>
                            <h2><?= $questionData['question'] ?></h2>

                            <!-- Si c'est une dictée avec audio -->
                            <?php if (isset($questionData['audio'])): ?>
                                <audio autoplay controls>
                                    <source src="<?= htmlspecialchars($questionData['audio']) ?>" type="audio/mpeg">
                                    Votre navigateur ne supporte pas l'audio.
                                </audio>
                            <?php endif; ?>

                            <form action="index.php?controller=module&action=correction&type=<?= htmlspecialchars($_GET['type']) ?>" method="post">
                                <input type="hidden" name="correction" value="<?= htmlspecialchars($questionData['answer']) ?>">
                                <input type="hidden" name="question" value="<?= htmlspecialchars($questionData['question']) ?>">
                                <input type="hidden" id="duree" name="duree" value="0">
                                <label for="mot">Ta réponse :</label>
                                <input type="text" id="mot" name="mot" autocomplete="off" autofocus required>
                                <input type="submit" value="Valider">
                            </form>
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

<script>
    let startTime = Date.now();
    document.querySelector("form").addEventListener("submit", function () {
        let endTime = Date.now();
        document.getElementById("duree").value = Math.floor((endTime - startTime) / 1000);
    });
</script>

<?php
require __DIR__ . '/../partials/footer.php';
?>
