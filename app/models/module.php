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
                
            case 'soustraction':
                $nb1 = mt_rand(6000, 10000);
                $nb2 = mt_rand(100, $nb1);
                return [
                    'question' => "$nb1 - $nb2 = ?",
                    'answer' => $nb1 - $nb2
                ];

            case 'multiplication':
                $nb1 = mt_rand(100, 10000);
                $nb2 = mt_rand(11, 99);
                return [
                    'question' => "$nb1 × $nb2 = ?",
                    'answer' => $nb1 * $nb2
                ];

            case 'dictee':
                $filePath = __DIR__ . "/listeDeMots/liste_dictee_20230407.txt"; // Assurez-vous que le chemin est correct
                
                    if (!file_exists($filePath)) {
                        return [
                            'question' => "Problème : fichier des phrases de conjugaison introuvable.",
                            'answer' => ""
                        ];
                    }
                
                    $fichier = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    if (!$fichier || empty($fichier)) {
                        return [
                            'question' => "Problème : fichier des phrases de conjugaison vide ou corrompu.",
                            'answer' => ""
                        ];
                    }
                $ligne = explode(';', $fichier[array_rand($fichier)]);
                $filePathSon = "sons/" . trim($ligne[1]);
                return [
                    'question' => "Écoute le fichier audio et écris le mot.",
                    'audio' => $filePathSon,
                    'answer' => trim($ligne[0])
                ];

                case 'conjugaison_phrase':
                    $filePath = __DIR__ . "/listeQuestions.txt"; // Assurez-vous que le chemin est correct
                
                    if (!file_exists($filePath)) {
                        return [
                            'question' => "Problème : fichier des phrases de conjugaison introuvable.",
                            'answer' => ""
                        ];
                    }
                
                    $fichier = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    if (!$fichier || empty($fichier)) {
                        return [
                            'question' => "Problème : fichier des phrases de conjugaison vide ou corrompu.",
                            'answer' => ""
                        ];
                    }
                
                    // Sélection aléatoire d'une ligne
                    $ligne = explode(';', $fichier[array_rand($fichier)]);
                
                    // Vérification du format
                    if (count($ligne) < 3) {
                        return [
                            'question' => "Problème : format incorrect dans le fichier des phrases.",
                            'answer' => ""
                        ];
                    }
                
                    // Extraction des éléments
                    $sujet = trim($ligne[0]); // Sujet de la phrase
                    $verbe = trim($ligne[1]); // Verbe à conjuguer
                    $finPhrase = trim($ligne[2]); // Fin de la phrase
                
                    // Construction de la phrase complète
                    $phraseComplete = (!empty($sujet) ? "$sujet " : "") . "**[$verbe]** $finPhrase";
                
                    return [
                        'question' => "Complète la phrase en conjuguant le verbe entre crochets : <br><br> $phraseComplete",
                        'answer' => "Réponse correcte attendue"
                    ];
                

            case 'conjugaison_verbe':
                return $this->generateConjugationQuestion();

            default:
                return [
                    'question' => "Exercice non défini.",
                    'answer' => ""
                ];
        }
    }

    private function generateConjugationQuestion()
    {
        // Détermine un temps au hasard
        $tempsOptions = ['present', 'imparfait', 'futur'];
        $temps = $tempsOptions[array_rand($tempsOptions)];
        // Sélectionne un verbe au hasard
        $filePath = __DIR__ . "/verbes/$temps.txt";
        $fichier = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $verbe = trim($fichier[array_rand($fichier)]);

        // Enlève les accents et les caractères spéciaux du verbe
        $verbeSansAccent = strtr($verbe, [
            'à' => 'a', 'â' => 'a', 'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u',
            'ü' => 'u', 'ÿ' => 'y', 'ç' => 'c'
        ]);

        $nomFichier = "\/verbes/" . $verbeSansAccent . "_$temps.txt";
        $filePath = __DIR__ . $nomFichier;
        if (!file_exists($filePath)) {
            return [
                'question' => "Problème : fichier de conjugaison introuvable pour le verbe $verbe au $temps.",
                'answer' => ""
            ];
        }

        $fichierVerbe = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $reponses = array_map('trim', $fichierVerbe);

        return [
            'question' => "Conjugue le verbe **<u>$verbe</u>** au **$temps**.",
            'answer' => "Reponse attendue"
        ];
    }
}
