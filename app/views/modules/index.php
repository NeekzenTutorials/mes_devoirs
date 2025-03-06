<?php
$title = $title;
require __DIR__ . '/../partials/header.php';
?>

<!-- Ajout du fichier CSS -->
<link rel="stylesheet" href="/css/modules_index.css">

<main>
    <div class="module-container">
        <table class="table-container" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="module-cell" style="background-image:url('/images/NO.jpg');">
                    <center>
                        <div class="form-container">
                            <h1>Bonjour !</h1>
                            <h2>Nous allons faire des <?= htmlspecialchars($title) ?>.</h2>
                            <h3>Mais avant, Quel est ton prénom ?</h3>
                            <form action="index.php?controller=module&action=start&type=<?= htmlspecialchars($_GET['type']) ?>" method="post">
                                <input type="text" id="prenom" name="prenom" autocomplete="off" autofocus required>
                                <input type="submit" value="Commencer">
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

<?php
require __DIR__ . '/../partials/footer.php';
?>
