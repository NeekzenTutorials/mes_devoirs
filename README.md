# mes_devoirs

* Auteur : Nathan FOURNY

Projet réalisé dans le cadre d'une SAE (projet) de 3ème année en BUT informatique à l'IUT de Calais.

## Remise en contexte

L'objectif de ce projet était la maintenance et l'amélioration de l'application "mes devoirs" dont le principe est de permettre d'effectuer des exercices de math et de français de niveau maternel.  

## Mise en place

* **Accéder au site**

Mettre en place le code dans un serveur apache, en utilisant MAMP ou équivalent par exemple.

* **Base de données**

Vous pouvez récupérer un bdd.sql qui permet de générer entièrement la base de données mysql.

## Inventaire des fonctionnalitées

* Connexion utilisateur
    1. Mot de passe haché et sécurisé en base
    2. Choix de rôle entre Parent | Enfant | Enseignant
    3. Si Enfant, choix du niveau entre CP et CM2
* Réalisation des exercices
    1. Série de 10 questions dans chacun des thèmes proposés
    2. Résumé des réponses avec correction à la fin de la série
* Profil utilisateur
    1. Affichage des informations de l'utilisateur
    2. Modification du Nom et Prénom possible
    3. Si Parent, peut ajouter et visualiser les profils de ses enfants
    4. Possibilité d'accéder à un dashboard qui présente des données globales sur les exercices réalisés
    5. Possibilité d'accéder à l'historique des séries réalisées
    6. Possibilité d'accéder en détail à chacune des séries réalisées et afficher les réponses et corrections des questions

* **Indiquation** : Pour lier un enfant et un parent, le système de notification n'est pas opérationnel, vous pouvez le faire en passer la valeur de la propriété : **statut**(type enum), de la table **Parente** à "accepte" pour visualiser son enfant depuis un profil parent. Vous pouvez le faire manuellement depuis PHPMyAdmin ou depuis un terminal sql.

## Style graphique

Le style graphique est resté similaire à celui d'origine.

## Expérience utilisateur

Ajout d'un header au site afin de revenir plus facilement à l'accueil, accéder à son profil et se déconnecter rapidement.

## Sécurité

* Mot de passe haché et sécurisé
* Impossible de réaliser des exercices si non connectés (kick de la session si déconnexion pendant un exercice)
* Confirmation de l'enfant lorsqu'un parent veut l'ajouter en tant qu'enfant

# Architecture

Refonte complète tu code du site d'origine.  
Passage dans une architecture en modèle MVC (Modèle Vue Controlleur).  
Suppression du code et des fichiers inutiles ou dupliqués.  

Nouvelle architecture :

- app
  - controllers
  - models
  - utils
  - views
- public
  - css
  - images
  - sons
  - index.html
