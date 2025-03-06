<?php

class AuthController
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function login()
    {
        // On peut définir un titre pour la vue, ou d'autres variables
        $title = "Connexion";

        // Inclure la vue login.php
        require __DIR__ . '/../Views/auth/connexion.php';
    }

    /**
     * Traite le formulaire de connexion (POST).
     */
    public function doLogin()
    {
        require_once __DIR__ . '/../models/db.php';
        session_start();

        // Récupérer et vérifier le formulaire
        $nom = trim($_POST['nom'] ?? '');
        $mdp = $_POST['mot_de_passe'] ?? '';

        // Sélection de l'utilisateur
        $sql = "SELECT * FROM utilisateurs WHERE nom = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Vérifie le hash
            if (password_verify($mdp, $row['mot_de_passe'])) {
                // Succès => enregistre en session
                $_SESSION['id_utilisateur'] = $row['id_utilisateur'];
                $_SESSION['nom']           = $row['nom'];
                $_SESSION['prenom']        = $row['prenom'];

                // Redirection
                header('Location: index.php?controller=home&action=index');
                exit;
            } else {
                // Mauvais mot de passe
                $_SESSION['erreur'] = "Mot de passe incorrect.";
            }
        } else {
            // Utilisateur introuvable
            $_SESSION['erreur'] = "Utilisateur introuvable.";
        }

        // Dans tous les cas, on revient sur le formulaire de login
        header('Location: index.php?controller=auth&action=login');
        exit;
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
        require_once __DIR__ . '/../../db.php';

        $nom         = trim($_POST['nom'] ?? '');
        $prenom      = trim($_POST['prenom'] ?? '');
        $role        = trim($_POST['role'] ?? '');
        $classe      = (int) ($_POST['classe'] ?? 0);
        $mot_clair   = $_POST['mot_de_passe'] ?? '';

        $mot_hash = password_hash($mot_clair, PASSWORD_DEFAULT);

        // Insertion table utilisateurs
        $sql_ins = "INSERT INTO utilisateurs (nom, prenom, mot_de_passe) VALUES (?, ?, ?)";
        $stmt_ins = $conn->prepare($sql_ins);
        $stmt_ins->bind_param("sss", $nom, $prenom, $mot_hash);

        if ($stmt_ins->execute()) {
            $id_user = $stmt_ins->insert_id;

            // Selon le rôle
            switch ($role) {
                case 'enfant':
                    if ($classe > 0) {
                        $sql_enf = "INSERT INTO enfants (id_utilisateur, id_classe) VALUES (?, ?)";
                        $stmt_enf = $conn->prepare($sql_enf);
                        $stmt_enf->bind_param("ii", $id_user, $classe);
                        $stmt_enf->execute();
                    }
                    break;

                case 'parent':
                    $sql_par = "INSERT INTO parents (id_utilisateur) VALUES (?)";
                    $stmt_par = $conn->prepare($sql_par);
                    $stmt_par->bind_param("i", $id_user);
                    $stmt_par->execute();
                    break;

                case 'enseignant':
                    $sql_ens = "INSERT INTO enseignants (id_utilisateur) VALUES (?)";
                    $stmt_ens = $conn->prepare($sql_ens);
                    $stmt_ens->bind_param("i", $id_user);
                    $stmt_ens->execute();
                    break;
            }

            // Redirection vers la connexion
            header('Location: index.php?controller=auth&action=login');
            exit;
        } else {
            echo "Erreur lors de l'inscription: " . $stmt_ins->error;
        }
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
