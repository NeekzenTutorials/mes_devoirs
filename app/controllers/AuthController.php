<?php

require_once __DIR__ . '/../models/db.php';

class AuthController
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function login()
    {
        $title = "Connexion";
        require __DIR__ . '/../Views/auth/connexion.php';
    }

    /**
     * Traite le formulaire de connexion (POST).
     */
    public function doLogin()
    {
        session_start();
        $db = Database::getConnection();

        // Récupérer et vérifier le formulaire
        $nom = trim($_POST['nom'] ?? '');
        $mdp = $_POST['mot_de_passe'] ?? '';

        // Vérification de l'utilisateur
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE nom = ? LIMIT 1");
        $stmt->execute([$nom]);
        $user = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            // Stockage en session
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            header('Location: index.php?controller=home&action=index');
            exit;
        } else {
            $_SESSION['erreur'] = "Nom ou mot de passe incorrect.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function register()
    {
        $title = "Inscription";
        require __DIR__ . '/../Views/auth/inscription.php';
    }

    /**
     * Traite le formulaire d'inscription (POST).
     */
    public function doRegister()
    {
        $db = Database::getConnection();

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $classe = (int) ($_POST['classe'] ?? 0);
        $mot_clair = $_POST['mot_de_passe'] ?? '';

        // Hash du mot de passe
        $mot_hash = password_hash($mot_clair, PASSWORD_DEFAULT);

        // Insertion dans `utilisateurs`
        $stmt = $db->prepare("INSERT INTO utilisateurs (nom, prenom, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $mot_hash]);
        $id_user = $db->lastInsertId();

        // Selon le rôle
        switch ($role) {
            case 'enfant':
                if ($classe > 0) {
                    $stmt = $db->prepare("INSERT INTO enfants (id_utilisateur, id_classe) VALUES (?, ?)");
                    $stmt->execute([$id_user, $classe]);
                }
                break;

            case 'parent':
                $stmt = $db->prepare("INSERT INTO parents (id_utilisateur) VALUES (?)");
                $stmt->execute([$id_user]);
                break;

            case 'enseignant':
                $stmt = $db->prepare("INSERT INTO enseignants (id_utilisateur) VALUES (?)");
                $stmt->execute([$id_user]);
                break;
        }

        // Redirection vers la connexion
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: index.php?controller=home&action=index');
        exit;
    }
}
?>
