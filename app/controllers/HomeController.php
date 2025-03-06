<?php
class HomeController
{
    public function index()
    {
        // Ici, on peut faire de la logique : 
        // ex. $user = User::find(1);

        // Puis on appelle la vue :
        // $title = "Accueil" sera géré dans la vue elle-même
        require __DIR__ . '/../Views/home/index.php';
    }
}
