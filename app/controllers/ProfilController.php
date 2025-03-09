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
        $stmt = $db->prepare("
            SELECT u.id_utilisateur, u.nom, u.prenom, 
                CASE 
                    WHEN e.id_utilisateur IS NOT NULL THEN 'enseignant'
                    WHEN p.id_utilisateur IS NOT NULL THEN 'parent'
                    WHEN en.id_utilisateur IS NOT NULL THEN 'enfant'
                    ELSE 'inconnu'
                END AS role
            FROM Utilisateurs u
            LEFT JOIN Enseignants e ON u.id_utilisateur = e.id_utilisateur
            LEFT JOIN Parents p ON u.id_utilisateur = p.id_utilisateur
            LEFT JOIN Enfants en ON u.id_utilisateur = en.id_utilisateur
            WHERE u.id_utilisateur = ?
        ");
        $stmt->execute([$idUtilisateur]);
        $user = $stmt->fetch();

        if ($user['role'] === 'parent') {
            $stmt = $db->prepare("
                SELECT u.id_utilisateur, u.nom, u.prenom 
                FROM Utilisateurs u
                INNER JOIN Parente p ON u.id_utilisateur = p.id_utilisateur
                WHERE p.id_utilisateur_1 = ? AND p.statut = 'accepte'
            ");
            $stmt->execute([$user['id_utilisateur']]);
            $enfants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $enfants = []; // Évite l'erreur si ce n'est pas un parent
        }

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

    public function ajouterEnfant()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            echo json_encode(["message" => "Vous devez être connecté."]);
            exit();
        }

        $db = Database::getConnection();
        $idParent = $_SESSION['id_utilisateur'];

        $nomEnfant = trim($_POST['nom_enfant'] ?? '');
        $prenomEnfant = trim($_POST['prenom_enfant'] ?? '');

        if (empty($nomEnfant) || empty($prenomEnfant)) {
            echo json_encode(["message" => "Veuillez renseigner le nom et le prénom de l'enfant."]);
            exit();
        }

        // Vérifier si l'enfant existe et est bien dans la table Enfants
        $stmt = $db->prepare("SELECT e.id_utilisateur FROM Utilisateurs u 
                            INNER JOIN Enfants e ON u.id_utilisateur = e.id_utilisateur
                            WHERE u.nom = ? AND u.prenom = ?");
        $stmt->execute([$nomEnfant, $prenomEnfant]);
        $enfant = $stmt->fetch();

        if (!$enfant) {
            echo json_encode(["message" => "Cet enfant n'existe pas ou n'est pas enregistré comme enfant."]);
            exit();
        }

        // Vérifier si la relation existe déjà
        $stmt = $db->prepare("SELECT id_utilisateur FROM Parente WHERE id_utilisateur = ? AND id_utilisateur_1 = ?");
        $stmt->execute([$enfant['id_utilisateur'], $idParent]);
        $lien = $stmt->fetch();

        if ($lien) {
            echo json_encode(["message" => "Cet enfant est déjà lié à votre compte."]);
        } else {
            $statut = 'en_attente';

            $stmt = $db->prepare("INSERT INTO Parente (id_utilisateur, id_utilisateur_1, statut) VALUES (?, ?, ?)");
            $stmt->execute([$enfant['id_utilisateur'], $idParent, $statut]);

            echo json_encode(["message" => "Demande envoyée à l'enfant."]);
        }
        exit();
    }

    public function voirEnfant()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $idParent = $_SESSION['id_utilisateur'];
        $idEnfant = (int)$_GET['id'];

        // Vérifier si l'utilisateur est bien parent de cet enfant
        $stmt = $db->prepare("SELECT id_utilisateur_1 FROM Parente WHERE id_utilisateur_1 = ? AND id_utilisateur = ?");
        $stmt->execute([$idParent, $idEnfant]);
        $lien = $stmt->fetch();

        if (!$lien) {
            // L'utilisateur n'est pas autorisé à voir l'historique de cet enfant
            header("Location: index.php?controller=profil&action=show");
            exit();
        }

        // Récupérer les sessions de l'enfant
        $stmt = $db->prepare("
            SELECT s.id_session, s.date_creation, COUNT(r.num_question) as nb_questions
            FROM Sessions s
            JOIN Realise r ON s.id_session = r.id_session
            WHERE s.id_utilisateur = ?
            GROUP BY s.id_session
            ORDER BY s.date_creation DESC
        ");
        $stmt->execute([$idEnfant]);
        $sessions = $stmt->fetchAll();

        require __DIR__ . '/../views/profil/history.php';
    }
}
