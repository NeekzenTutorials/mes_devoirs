<?php

function supprime_caracteres_speciaux($chaine) {
    return strtr($chaine, [
        'à' => 'a', 'â' => 'a', 'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
        'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u',
        'ü' => 'u', 'ÿ' => 'y', 'ç' => 'c'
    ]);
}

function conjugaison($nomFichier, $numLigne) {
    if (!file_exists($nomFichier)) {
        return "Fichier de conjugaison introuvable.";
    }
    $fichierVerbe = file($nomFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$fichierVerbe || count($fichierVerbe) < $numLigne) {
        return "Erreur de conjugaison.";
    }
    return trim($fichierVerbe[$numLigne - 1]);
}

?>