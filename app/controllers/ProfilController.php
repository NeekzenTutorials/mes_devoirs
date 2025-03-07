<?php

require_once __DIR__ . '/../models/db.php';

class ProfilController
{
    /**
     * Affiche la page de profil (informations de l'utilisateur + bouton pour modifier).
     */
    public function show()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $idUtilisateur = $_SESSION['id_utilisateur'];

        // Récupérer les infos de l'utilisateur
        $stmt = $db->prepare("SELECT * FROM Utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$idUtilisateur]);
        $user = $stmt->fetch();

        require __DIR__ . '/../views/profil/show.php';
    }

    /**
     * Traite la modification du profil (nom, prénom).
     */
    public function update()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $idUtilisateur = $_SESSION['id_utilisateur'];

        $nom    = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        // Mettre à jour
        $stmt = $db->prepare("UPDATE Utilisateurs SET nom = ?, prenom = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nom, $prenom, $idUtilisateur]);

        // Redirection vers le profil
        header("Location: index.php?controller=profil&action=show");
        exit();
    }

    /**
     * Affiche le tableau de bord avec statistiques.
     */
    public function dashboard()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $idUtilisateur = $_SESSION['id_utilisateur'];

        // Exemple : compter le nombre total d'exercices réalisés
        $stmt = $db->prepare("SELECT COUNT(*) as total_exos FROM Realise WHERE id_utilisateur = ?");
        $stmt->execute([$idUtilisateur]);
        $row = $stmt->fetch();
        $nbExercices = $row['total_exos'];

        // Exemple : compter le nombre de réponses valides
        $stmt = $db->prepare("SELECT COUNT(*) as total_valides FROM Realise WHERE id_utilisateur = ? AND valide = 1");
        $stmt->execute([$idUtilisateur]);
        $row = $stmt->fetch();
        $nbValides = $row['total_valides'];

        // Pourcentage de réussite
        $pourcentage = 0;
        if ($nbExercices > 0) {
            $pourcentage = round(($nbValides / $nbExercices) * 100, 2);
        }

        // On peut aussi déterminer les "thèmes favoris" : 
        // par exemple, s'appuyer sur la table Exercices (jointure) pour compter le plus grand nombre de réponses valides par type...
        // Ceci est un exemple simplifié
        $stmt = $db->prepare("
            SELECT e.type, COUNT(r.id_exercice) as nb 
            FROM Realise r
            JOIN Exercices e ON r.id_exercice = e.id_exercice
            WHERE r.id_utilisateur = ? 
            GROUP BY e.type 
            ORDER BY nb DESC 
            LIMIT 1
        ");
        $stmt->execute([$idUtilisateur]);
        $fav = $stmt->fetch();
        $themeFavori = $fav ? $fav['type'] : 'Aucun';

        require __DIR__ . '/../views/profil/dashboard.php';
    }

    /**
     * Liste toutes les sessions de l'utilisateur (Historique).
     */
    public function history()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $idUtilisateur = $_SESSION['id_utilisateur'];

        // Récupérer les sessions de l'utilisateur
        $stmt = $db->prepare("
            SELECT s.id_session, s.date_creation, COUNT(r.num_question) as nb_questions
            FROM Sessions s
            JOIN Realise r ON s.id_session = r.id_session
            WHERE s.id_utilisateur = ?
            GROUP BY s.id_session
            ORDER BY s.date_creation DESC
        ");
        $stmt->execute([$idUtilisateur]);
        $sessions = $stmt->fetchAll();

        require __DIR__ . '/../views/profil/history.php';
    }

    /**
     * Affiche le résumé d'une session particulière (détails).
     */
    public function sessionDetail()
    {

        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $idSession = (int)$_GET['id_session'];

        // Récupérer toutes les réponses de la session
        $stmt = $db->prepare("
            SELECT r.num_question, r.reponse, r.valide, r.duree,
                   e.type, e.enonce, e.resultat
            FROM Realise r
            JOIN Exercices e ON r.id_exercice = e.id_exercice
            WHERE r.id_utilisateur = ? AND r.id_session = ?
            ORDER BY r.num_question ASC
        ");
        $stmt->execute([$idUtilisateur, $idSession]);
        $reponses = $stmt->fetchAll();

        require __DIR__ . '/../views/profil/sessionDetail.php';
    }
}
