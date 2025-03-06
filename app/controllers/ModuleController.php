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
        require __DIR__ . '/../Views/modules/index.php';
    }

    public function question()
    {
        session_start();
        $_SESSION['origine'] = "question";

        if (!isset($_SESSION['prenom']) || empty($_SESSION['prenom'])) {
            if (!isset($_POST['prenom']) || empty($_POST['prenom'])) {
                header('Location: index.php?controller=module&action=show&type=' . $_GET['type']);
                exit();
            }
            $_SESSION['prenom'] = $_POST['prenom'];
        }

        $_SESSION['nbQuestion'] = isset($_SESSION['nbQuestion']) ? $_SESSION['nbQuestion'] + 1 : 1;

        require_once __DIR__ . '/../Models/Module.php';
        $module = new Module();
        $questionData = $module->generateQuestion($_GET['type']);

        require __DIR__ . '/../Views/modules/question.php';
    }
}
