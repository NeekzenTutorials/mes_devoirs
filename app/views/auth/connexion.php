<?php
// On peut utiliser $title si on veut. Ex: <title><?= $title </title>
// On inclut éventuellement un header commun :
require __DIR__ . '/../partials/header.php';
?>
<link rel="stylesheet" href="/css/connexion.css">

<?php
// Affichage d'un message d'erreur stocké en session, si besoin
if (!empty($_SESSION['erreur'])) {
    echo '<p style="color:red;">' . $_SESSION['erreur'] . '</p>';
    unset($_SESSION['erreur']);
}
?>

<form class="form-container" action="index.php?controller=auth&action=doLogin" method="post">
    <label for="nom">Nom :</label><br>
    <input type="text" name="nom" id="nom" required><br><br>

    <label for="mot_de_passe">Mot de passe :</label><br>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required><br><br>

    <input type="submit" value="Se connecter">
</form>

<?php
// Footer commun
require __DIR__ . '/../partials/footer.php';