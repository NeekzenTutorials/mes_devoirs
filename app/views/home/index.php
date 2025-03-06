<?php
$title = "Accueil"; // Spécifie le titre
require __DIR__ . '/../partials/header.php';
?>

<!-- Lien vers le fichier CSS externe -->
<link rel="stylesheet" href="/css/index.css">

<!-- Contenu principal -->
<main>
    <table class="table-container" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="module-cell" style="background-image:url('/images/NO.jpg');">
                <center>
                    <h1>Bonjour !</h1>
                    <h2>Que veux-tu faire ?</h2>

                    <table class="modules-table">
                        <tr>
                            <td>
                                <center>
                                    <a href="index.php?controller=module&action=show&type=addition">
                                        <img src="/images/addition.png"><br />Addition
                                    </a>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <a href="index.php?controller=module&action=show&type=soustraction">
                                        <img src="/images/soustraction.png"><br />Soustraction
                                    </a>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <a href="index.php?controller=module&action=show&type=multiplication">
                                        <img src="/images/multiplication.png"><br />Multiplication
                                    </a>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center>
                                    <a href="index.php?controller=module&action=show&type=dictee">
                                        <img src="/images/dictee.png"><br />Dictée
                                    </a>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <a href="index.php?controller=module&action=show&type=conjugaison_verbe">
                                        <img src="/images/conjugaison_verbe.png"><br />Conjugaison<br />de verbes
                                    </a>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <a href="index.php?controller=module&action=show&type=conjugaison_phrase">
                                        <img src="/images/conjugaison_phrase.png"><br />Conjugaison<br />de phrases
                                    </a>
                                </center>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
            <td class="side-cell" style="background-image:url('/images/NE.jpg');"></td>
        </tr>
        <tr>
            <td class="module-cell" style="background-image:url('/images/SO.jpg');"></td>
            <td class="side-cell" style="background-image:url('/images/SE.jpg');"></td>
        </tr>
    </table>
</main>

<?php
require __DIR__ . '/../partials/footer.php';
?>
