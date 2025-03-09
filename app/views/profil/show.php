<?php
$title = "Mon Profil";
require __DIR__ . '/../partials/header.php';
?>

<link rel="stylesheet" href="/css/profil.css">

<main>
    <div class="form-container">
        <h1>Mon Profil</h1>

        <p>Nom : <?= htmlspecialchars($user['nom']) ?></p>
        <p>Prénom : <?= htmlspecialchars($user['prenom']) ?></p>

        <form action="index.php?controller=profil&action=update" method="POST">
            <label for="nom">Modifier Nom :</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($user['nom']) ?>">

            <label for="prenom">Modifier Prénom :</label>
            <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">

            <button type="submit">Enregistrer</button>
        </form>

        <a href="index.php?controller=profil&action=dashboard">Mon Tableau de Bord</a><br>
        <a href="index.php?controller=profil&action=history">Mon Historique</a>
        <!-- Ajout de l'espace Parent -->
        <?php if (isset($user['role']) && $user['role'] === 'parent') : ?>
        
            <h2>Ajouter un enfant</h2>
            <form action="index.php?controller=profil&action=ajouterEnfant" method="POST">
                <label for="nom_enfant">Nom de l'enfant :</label>
                <input type="text" name="nom_enfant" required>

                <label for="prenom_enfant">Prénom de l'enfant :</label>
                <input type="text" name="prenom_enfant" required>

                <button type="submit">Ajouter</button>
            </form>
            <p id="message"></p>

            <h2>Mes enfants</h2>
            <ul>
                <?php foreach ($enfants as $enfant) : ?>
                    <li>
                        <?= htmlspecialchars($enfant['prenom']) ?> <?= htmlspecialchars($enfant['nom']) ?>
                        <a href="index.php?controller=profil&action=voirEnfant&id=<?= $enfant['id_utilisateur'] ?>">📂 Voir le profil</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>

<script>
document.getElementById("ajouterEnfantForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche le rechargement de la page

    let formData = new FormData(this);

    fetch("index.php?controller=profil&action=ajouterEnfant", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // On attend une réponse JSON
    .then(data => {
        document.getElementById("message").innerText = data.message; // Afficher le message
    })
    .catch(error => console.error("Erreur :", error));
});
</script>

<?php
require __DIR__ . '/../partials/footer.php';
?>
