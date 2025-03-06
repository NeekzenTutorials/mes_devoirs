<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Définir la racine du projet
define('ROOT', dirname(__DIR__));

// Charger config, autoload, etc.
// require ROOT . '/app/config.php';

// Déterminer le contrôleur et l’action
$controller = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'home';
$action     = isset($_GET['action']) ? strtolower($_GET['action']) : 'index';

// Exemple : "HomeController"
$controllerClass = ucfirst($controller) . 'Controller';
// Chemin physique : "app/Controllers/HomeController.php"
$controllerFile = ROOT . '/app/Controllers/' . $controllerClass . '.php';

// Vérifier existence
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Possiblement : use App\Controllers\HomeController;
    // Mais si pas de namespace, on fait simplement :
    if (class_exists($controllerClass)) {
        $obj = new $controllerClass();
        if (method_exists($obj, $action)) {
            $obj->$action();
        } else {
            http_response_code(404);
            echo "Action `$action` introuvable dans `$controllerClass`.";
        }
    } else {
        http_response_code(404);
        echo "Classe `$controllerClass` introuvable.";
    }
} else {
    http_response_code(404);
    echo "Contrôleur `$controllerClass` introuvable.";
}
