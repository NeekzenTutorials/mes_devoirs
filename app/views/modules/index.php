<?php
$title = $title;
require __DIR__ . '/../partials/header.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: index.php?controller=auth&action=login");
    exit();
}
?>

<!-- Ajout du fichier CSS -->
<link rel="stylesheet" href="/css/module_index.css">

<main>
    <div class="module-container">
        <table class="table-container" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="module-cell" style="background-image:url('/images/NO.jpg');">
                    <center>
                        <div class="form-container">
                            <h1>Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?> !</h1>
                            <h2>Nous allons faire des <?= htmlspecialchars($title) ?>.</h2>
                            <h3>Choisissez le nombre de questions :</h3>

                            <form action="index.php?controller=module&action=question&type=<?= htmlspecialchars($_GET['type']) ?>" method="post">
                                <label for="nb_questions">Nombre de questions :</label>
                                <select name="nb_questions" id="nb_questions">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <br><br>

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
