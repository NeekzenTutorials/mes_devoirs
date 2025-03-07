<?php

require_once __DIR__ . '\..\utils\utils.php';

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
                $filePath = __DIR__ . "/listeDeMots/liste_dictee_20230407.txt";
                
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
                    $filePath = __DIR__ . "/listeQuestions.txt";
                
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
                    $numPronom = mb_substr($ligne[0], 0, 1); // Extraire le premier caractère
                    $sujet = trim(mb_substr($ligne[0], 1)); // Extraire le sujet
                    $verbe = trim($ligne[1]); // Verbe à conjuguer
                    $finPhrase = trim($ligne[2]); // Fin de la phrase
                
                    // Génération du sujet aléatoire si nécessaire
                    if ($numPronom == "*") {
                        $numPronom = mt_rand(1, 6);
                        switch ($numPronom) {
                            case 1:
                                $sujet = "Je";
                                break;
                            case 2:
                                $sujet = "Tu";
                                break;
                            case 3:
                                $sujet = mt_rand(0, 2) === 0 ? "Elle" : (mt_rand(0, 1) ? "Il" : "On");
                                break;
                            case 4:
                                $sujet = "Nous";
                                break;
                            case 5:
                                $sujet = "Vous";
                                break;
                            case 6:
                                $sujet = mt_rand(0, 1) === 0 ? "Ils" : "Elles";
                                break;
                        }
                    }
                
                    // Déterminer la bonne réponse
                    $verbeSansAccent = supprime_caracteres_speciaux($verbe);
                    $filePath = __DIR__ . "\..\\models\\verbes\\" . $verbeSansAccent . "_present.txt";
                    $nomFichier = $filePath;
                    $bonneReponse = conjugaison($nomFichier, $numPronom);
                
                    // Vérification si "Je" devient "J'"
                    $bonneReponseSansAccent = supprime_caracteres_speciaux($bonneReponse);
                    if ($sujet == "Je" && in_array(substr($bonneReponseSansAccent, 0, 1), ['a', 'e', 'i', 'o', 'u'])) {
                        $sujet = "J'";
                    }
                
                    // Construire la phrase avec le verbe en gras entre crochets
                    $phraseComplete = "$sujet **[$verbe]** $finPhrase";
                
                    return [
                        'question' => "Complète la phrase en conjuguant le verbe entre crochets : <br><br> $phraseComplete",
                        'answer' => trim($bonneReponse)
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

        // Vérifie si le fichier contenant les verbes du temps choisi existe
        $filePathVerbes = __DIR__ . "/verbes/$temps.txt";
        if (!file_exists($filePathVerbes)) {
            return [
                'question' => "Problème : fichier de verbes introuvable pour le temps $temps.",
                'answer' => ""
            ];
        }

        // Sélectionne un verbe au hasard depuis le fichier de temps
        $fichierVerbes = file($filePathVerbes, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $verbe = trim($fichierVerbes[array_rand($fichierVerbes)]);

        // Enlève les accents et les caractères spéciaux du verbe
        $verbeSansAccent = strtr($verbe, [
            'à' => 'a', 'â' => 'a', 'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u',
            'ü' => 'u', 'ÿ' => 'y', 'ç' => 'c'
        ]);

        // Chemin du fichier de conjugaison pour ce verbe et ce temps
        $filePathConjugaison = __DIR__ . "/verbes/" . $verbeSansAccent . "_$temps.txt";

        // Vérifie si le fichier de conjugaison existe
        if (!file_exists($filePathConjugaison)) {
            return [
                'question' => "Problème : fichier de conjugaison introuvable pour le verbe $verbe au $temps.",
                'answer' => ""
            ];
        }

        // Liste des sujets possibles avec leurs indices correspondants dans le fichier de conjugaison
        $sujets = [
            1 => "Je",
            2 => "Tu",
            3 => ["Il", "Elle", "On"], // Choix aléatoire
            4 => "Nous",
            5 => "Vous",
            6 => ["Ils", "Elles"] // Choix aléatoire
        ];

        // Sélectionne un sujet au hasard
        $numPronom = mt_rand(1, 6);
        $sujet = is_array($sujets[$numPronom]) ? $sujets[$numPronom][array_rand($sujets[$numPronom])] : $sujets[$numPronom];

        // Charge le fichier de conjugaison et récupère la réponse attendue
        $fichierConjugaison = file($filePathConjugaison, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$fichierConjugaison || count($fichierConjugaison) < $numPronom) {
            return [
                'question' => "Problème : fichier de conjugaison incomplet pour le verbe $verbe.",
                'answer' => ""
            ];
        }

        $bonneReponse = trim($fichierConjugaison[$numPronom - 1]);

        // Vérification si "Je" doit devenir "J'"
        $bonneReponseSansAccent = strtr($bonneReponse, [
            'à' => 'a', 'â' => 'a', 'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u',
            'ü' => 'u', 'ÿ' => 'y', 'ç' => 'c'
        ]);

        if ($sujet == "Je" && in_array(substr($bonneReponseSansAccent, 0, 1), ['a', 'e', 'i', 'o', 'u'])) {
            $sujet = "J'";
        }

        return [
            'question' => "Conjugue : $sujet **$verbe** | au temps : $temps.",
            'answer' => $bonneReponse
        ];
    }

}
