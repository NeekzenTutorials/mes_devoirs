<?php

require_once __DIR__ . '/../models/db.php';

class ModuleController
{

    public function show()
    {
        // Vérifie si un type de module a été fourni
        $type = isset($_GET['type']) ? $_GET['type'] : 'addition';

        // Définition des textes en fonction du module
        $modules = [
            'addition' => ['title' => 'Addition', 'subtitle' => 'Tu vas devoir faire des additions.'],
            'soustraction' => ['title' => 'Soustraction', 'subtitle' => 'Tu vas devoir faire des soustractions.'],
            'multiplication' => ['title' => 'Multiplication', 'subtitle' => 'Résous des multiplications.'],
            'dictee' => ['title' => 'Dictée', 'subtitle' => 'Écris correctement le mot dicté.'],
            'conjugaison_verbe' => ['title' => 'Conjugaison de Verbes', 'subtitle' => 'Conjugue le verbe donné.'],
            'conjugaison_phrase' => ['title' => 'Conjugaison de Phrases', 'subtitle' => 'Complète la phrase avec le bon verbe.']
        ];

        // Vérifie si le type existe dans les modules, sinon met un par défaut
        $title = isset($modules[$type]) ? $modules[$type]['title'] : 'Exercice';
        $subtitle = isset($modules[$type]) ? $modules[$type]['subtitle'] : 'Amuse-toi en pratiquant !';

        // Charge la vue avec les variables dynamiques
        require __DIR__ . '/../views/modules/index.php';
    }

    /**
     * Démarre une nouvelle session d'exercice.
     */
    public function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $db = Database::getConnection();
        $type = $_GET['type'] ?? 'addition';
        $nbQuestions = isset($_POST['nb_questions']) ? (int)$_POST['nb_questions'] : 10;

        // Réinitialisation de la session en PHP
        $_SESSION['id_session'] = null;
        $_SESSION['nbMaxQuestions'] = $nbQuestions;
        $_SESSION['nbQuestion'] = 0;
        $_SESSION['nbBonneReponse'] = 0;
        $_SESSION['questions'] = [];

        // Insérer une nouvelle session en base
        $stmt = $db->prepare("INSERT INTO Sessions (id_utilisateur) VALUES (?)");
        $stmt->execute([$_SESSION['id_utilisateur']]);
        $_SESSION['id_session'] = $db->lastInsertId();

        header("Location: index.php?controller=module&action=question&type=$type");
        exit();
    }

    /**
     * Génère et affiche une question.
     */
    public function question()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/module.php';
        $db = Database::getConnection();

        if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['id_session'])) {
            header("Location: index.php?controller=module&action=startSession&type=" . htmlspecialchars($_GET['type']));
            exit();
        }

        if ($_SESSION['nbQuestion'] >= $_SESSION['nbMaxQuestions']) {
            header("Location: index.php?controller=module&action=summary");
            exit();
        }

        $type = $_GET['type'];
        $module = new Module();
        $questionData = $module->generateQuestion($type);

        // Vérifier si l'exercice existe en base
        $stmt = $db->prepare("SELECT id_exercice FROM Exercices WHERE type = ? AND enonce = ? AND resultat = ?");
        $stmt->execute([$type, $questionData['question'], $questionData['answer']]);
        $exercice = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exercice) {
            $idExercice = $exercice['id_exercice'];
        } else {
            // Insérer l'exercice s'il n'existe pas encore
            $stmt = $db->prepare("INSERT INTO Exercices (type, enonce, resultat) VALUES (?, ?, ?)");
            $stmt->execute([$type, $questionData['question'], $questionData['answer']]);
            $idExercice = $db->lastInsertId();
        }

        $_SESSION['questions'][$_SESSION['nbQuestion']] = [
            'id_exercice' => $idExercice,  // Stocker l'ID de l'exercice
            'question' => $questionData['question'],
            'answer' => $questionData['answer']
        ];

        $_SESSION['nbQuestion']++;

        require __DIR__ . '/../views/modules/question.php';
    }

    /**
     * Enregistre la réponse et passe à la question suivante.
     */
    public function correction()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $db = Database::getConnection();

        if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['id_session'])) {
            header("Location: index.php?controller=module&action=startSession&type=" . htmlspecialchars($_GET['type']));
            exit();
        }

        $type = $_GET['type'];
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $idSession = $_SESSION['id_session'];
        $numQuestion = $_SESSION['nbQuestion'] - 1;
        $reponseUtilisateur = trim($_POST['mot']);
        $reponseAttendue = trim($_POST['correction']);
        $duree = isset($_POST['duree']) ? (int)$_POST['duree'] : 0;
        $valide = (strcasecmp($reponseUtilisateur, $reponseAttendue) == 0) ? 1 : 0;

        // Vérifier que la question stockée en session contient bien l'ID de l'exercice
        if (!isset($_SESSION['questions'][$numQuestion]['id_exercice'])) {
            $_SESSION['erreur'] = "Problème avec l'exercice, veuillez recommencer.";
            header("Location: index.php?controller=module&action=startSession&type=$type");
            exit();
        }

        $idExercice = $_SESSION['questions'][$numQuestion]['id_exercice'];

        $_SESSION['questions'][$numQuestion]['user_answer'] = $reponseUtilisateur;
        $_SESSION['questions'][$numQuestion]['valid'] = $valide;
        $_SESSION['questions'][$numQuestion]['duration'] = $duree;

        if ($valide) {
            $_SESSION['nbBonneReponse']++;
        }

        $stmt = $db->prepare("SELECT COUNT(*) FROM Realise WHERE id_utilisateur = ? AND id_exercice = ? AND id_session = ? AND num_question = ?");
        $stmt->execute([$idUtilisateur, $idExercice, $idSession, $numQuestion + 1]);
        $count = $stmt->fetchColumn();

        if ($count == 0) { // Si aucune entrée, on insère
            $stmt = $db->prepare("INSERT INTO Realise (id_utilisateur, id_exercice, id_session, num_question, reponse, duree, valide) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$idUtilisateur, $idExercice, $idSession, $numQuestion + 1, $reponseUtilisateur, $duree, $valide]);
        }

        if ($_SESSION['nbQuestion'] >= $_SESSION['nbMaxQuestions']) {
            header("Location: index.php?controller=module&action=summary");
        } else {
            header("Location: index.php?controller=module&action=question&type=$type");
        }
        exit();
    }

    /**
     * Affiche le résumé final.
     */
    public function summary()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['id_session'])) {
            header("Location: index.php?controller=home&action=index");
            exit();
        }

        require __DIR__ . '/../views/modules/summary.php';

        unset($_SESSION['id_session']);
        unset($_SESSION['questions']);
    }
}
