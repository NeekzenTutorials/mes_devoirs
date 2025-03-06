<?php

class ModuleController
{
    public function show()
    {
        // Vérifie si un type de module a été fourni
        $type = isset($_GET['type']) ? $_GET['type'] : 'addition';

        // Définition du titre et du texte en fonction du module
        $modules = [
            'addition' => 'Additions',
            'soustraction' => 'Soustractions',
            'multiplication' => 'Multiplications',
            'dictee' => 'Dictées',
            'conjugaison_verbe' => 'Conjugaison de verbes',
            'conjugaison_phrase' => 'Conjugaison de phrases'
        ];

        // Vérifie si le type existe dans les modules, sinon met un par défaut
        $title = isset($modules[$type]) ? $modules[$type] : 'Calcul Mental';

        // Charge la vue en passant le titre et le type de module
        require __DIR__ . '/../Views/modules/index.php';
    }
}
