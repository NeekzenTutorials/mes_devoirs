<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Accueil' ?></title>
    <!-- Vous pouvez ajouter une feuille de style si besoin -->
    <!-- <link rel="stylesheet" href="/css/style.css"> -->
    <style>
        header {
            background-color: #45a1ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 20px;
        }
        .header-left a {
            text-decoration: none;
            font-weight: bold;
            color: white;
            padding: 8px;
            border-radius: 4px;
        }
        .header-title {
            flex: 1;
            text-align: center;
            margin: 0;
            color: white;
            font-size: 1.5em;
        }
        .header-right a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin-left: 10px;
            padding: 8px;
            border-radius: 4px;
        }
        .header-left a:hover,
        .header-right a:hover {
            background-color: #ff7700;
        }
    </style>
</head>
<body>
<header>
    <div class="header-left">
        <a href="/">Accueil</a>
    </div>
    <h1 class="header-title">Mes devoirs</h1>
    <div class="header-right">
        <?php if (!empty($_SESSION['nom']) && !empty($_SESSION['prenom'])): ?>
            <!-- Si l'utilisateur est connecté, on affiche prénom + nom + lien déconnexion -->
            <?= htmlspecialchars($_SESSION['prenom']) . ' ' . htmlspecialchars($_SESSION['nom']); ?>
            <a href="index.php?controller=profil&action=show">Mon Profil</a>
            <a href="index.php?controller=auth&action=logout">Déconnexion</a>
        <?php else: ?>
            <!-- Sinon, boutons Connexion & Inscription -->
            <a href="index.php?controller=auth&action=login">Connexion</a>
            <a href="index.php?controller=auth&action=register">Inscription</a>
        <?php endif; ?>
    </div>
</header>
