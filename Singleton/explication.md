# Singleton

## Problème
Dans une application, certaines ressources doivent absolument être **uniques**.
S'il existe plusieurs versions de la même "vérité" (plusieurs configurations, plusieurs connexions à la base de données), cela crée des conflits et gaspille de la mémoire.

Le problème est de garantir qu'une classe n'ait **qu'une seule et unique instance** et de fournir un moyen facile d'y accéder de partout (sans avoir à passer cette instance de fonction en fonction).

## Principe de fonctionnement
Le Singleton repose sur un principe simple : la classe **se contrôle elle-même**.
Elle interdit à quiconque de la créer avec `new` (en cachant son constructeur) et force tout le monde à passer par une méthode spéciale (`getInstance`) qui renvoie toujours la même instance déjà créée. Si elle n'existe pas encore, elle la crée à la volée.

## Structure (Rôles)
-   **La Classe Singleton** :
    -   Possède une variable statique privée pour stocker "l'unique objet".
    -   Possède un constructeur **privé** (personne ne peut l'appeler de l'extérieur).
    -   Offre une méthode publique statique (`getInstance`) qui gère la création et l'accès.

## Avantages
-   Assurance d'avoir une seule instance (contrôle strict).
-   Point d'accès global à cette instance.
-   Économie de mémoire (on ne recrée pas l'objet 50 fois).
-   Initialisation tardive (l'objet n'est créé que si on en a besoin).

## Inconvénients
-   Masque les dépendances : On ne voit pas dans la signature des fonctions qu'elles utilisent ce Singleton.
-   Difficile à tester : L'état global persiste entre les tests unitaires.
-   SRP (Single Responsibility Principle) : La classe fait deux choses : son métier ET sa propre gestion d'instance.
-   Concurrence : En multi-thread, il faut faire attention à ne pas créer deux instances en même temps.

## Cas d'usage réel possible
-   **Connexion à une Base de Données** : Plutôt que de rouvrir une connexion à chaque requête, on garde une connexion ouverte partagée.
-   **Système de Journalisation (Logger)** : Un seul fichier de logs écrit par un seul objet pour éviter les conflits d'écriture.
-   **Configuration Globale** : Charger les préférences de l'application une fois au démarrage et les garder accessibles partout.
