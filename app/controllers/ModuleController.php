<?php

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

    public function question()
    {
        $_SESSION['origine'] = "question";

        if (!isset($_SESSION['prenom']) || empty($_SESSION['prenom'])) {
            if (!isset($_POST['prenom']) || empty($_POST['prenom'])) {
                header('Location: index.php?controller=module&action=show&type=' . $_GET['type']);
                exit();
            }
            $_SESSION['prenom'] = $_POST['prenom'];
        }

        $_SESSION['nbQuestion'] = isset($_SESSION['nbQuestion']) ? $_SESSION['nbQuestion'] + 1 : 1;

        require_once __DIR__ . '/../models/module.php';
        $module = new Module();
        $questionData = $module->generateQuestion($_GET['type']);

        require __DIR__ . '/../views/modules/question.php';
    }

    public function correction()
    {
        require_once __DIR__ . '/../models/db.php';
        require_once __DIR__ . '/../models/module.php';

        if (!isset($_SESSION['id_utilisateur'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        // Récupérer les données du formulaire
        $type = $_GET['type'];
        $reponseUtilisateur = isset($_POST['mot']) ? trim($_POST['mot']) : "";
        $reponseAttendue = isset($_POST['correction']) ? trim($_POST['correction']) : "";
        $duree = isset($_POST['duree']) ? (int)$_POST['duree'] : 0;
        $idUtilisateur = $_SESSION['id_utilisateur'];

        // Vérifier si la réponse est correcte
        $valide = (strcasecmp($reponseUtilisateur, $reponseAttendue) == 0) ? 1 : 0;

        // Enregistrement de l'exercice en base de données
        $db = Database::getConnection();

        // Vérifier si l'exercice existe déjà dans la base
        $stmt = $db->prepare("SELECT id_exercice FROM Exercices WHERE type = ? AND enonce = ? AND resultat = ?");
        $stmt->execute([$type, $_POST['question'], $reponseAttendue]);
        $exercice = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exercice) {
            $idExercice = $exercice['id'];
        } else {
            // Insérer l'exercice s'il n'existe pas encore
            $stmt = $db->prepare("INSERT INTO Exercices (type, enonce, resultat) VALUES (?, ?, ?)");
            $stmt->execute([$type, $_POST['question'], $reponseAttendue]);
            $idExercice = $db->lastInsertId();
        }

        // Enregistrement de la tentative de l'utilisateur
        $stmt = $db->prepare("INSERT INTO Realise (id_utilisateur, id_exercice, reponse, duree, valide) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$idUtilisateur, $idExercice, $reponseUtilisateur, $duree, $valide]);

        // Affichage du résultat
        require __DIR__ . '/../views/modules/result.php';
    }

}
