<?php

class Module
{
    public function generateQuestion($type)
    {
        switch ($type) {
            case 'addition':
                $nb1 = mt_rand(1000, 10000);
                $nb2 = mt_rand(5000, 10000);
                return [
                    'question' => "$nb1 + $nb2 = ?",
                    'answer' => $nb1 + $nb2
                ];
            case 'dictee':
                $fichier = file("listeDeMots/liste_dictee_20230407.txt");
                $ligne = explode(';', $fichier[array_rand($fichier)]);
                return [
                    'question' => "Écoute le fichier audio et écris le mot.",
                    'audio' => "./sons/" . trim($ligne[1]),
                    'answer' => trim($ligne[0])
                ];
            case 'conjugaison_phrase':
                $fichier = file("listeQuestions.txt");
                $ligne = explode(';', $fichier[array_rand($fichier)]);
                return [
                    'question' => "Conjugue le verbe **{$ligne[1]}** dans cette phrase : {$ligne[2]}",
                    'answer' => "Réponse correcte attendue"
                ];
            default:
                return [
                    'question' => "Exercice non défini.",
                    'answer' => ""
                ];
        }
    }
}
