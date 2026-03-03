# Strategy (Stratégie) - Résumé Rapide

**But**
Extraire un algorithme (une façon de procéder) dans une classe parfaitement séparée pour pouvoir interchanger instantanément ces algorithmes d'une simple ligne de code du client.

**Problème résolu**
- Une classe "Outil" (`NavigateurGPS` ou `ExportateurPdf`) devient envahie de 5000 lignes dans un `if / else if / else` gigantesque au fur et à mesure que l'équipe y ajoute de "nouveaux modes". La maintenance devient intenable.

**La Solution**
Isoler chaque logique complexe dans sa propre classe (une **Stratégie**). La classe maîtresse (le **Contexte**) ne fait plus l'action : elle possède une méthode setter (`setStrategie()`) et elle donne l'ordre final à la stratégie actuellement équipée.

---

### Les 3 Éléments Clés (Structure)

1. **L'Interface / Stratégie Abstraite** (`StrategieItineraire`) : *Contrat commun exigeant à tout le monde la méthode d'action `calculerItineraire()`.*
2. **Stratégies Concrètes** (`ItineraireVoiture`, `ItineraireVelo`, ...) : *Contient véritablement l'algorithme complet lié à cet unique cas. Indépendante des autres moyens de se déplacer.*
3. **Le Contexte** (`NavigateurGPS`) : *Contient un pointeur sur une Stratégie. Offre un "setStrategie" pour pouvoir changer de cartouche/arme/algorithme à n'importe quel moment au "runtime". Il appelle lui-même cet algorithme quand l'action est ordonnée par le client.*

---

### Avantages
- **Open-Closed Principle respecté** : On rajoute le trajet en U-LM (`ItineraireULM`) en créant juste un fichier isolé sans toucher une virgule au classique `NavigateurGPS`.
- **Destruction de la méthode "Dieu" remplie de conditions (if/else géants)**.
- **Interchangeabilité "A la volée"** : Le client change de façon de faire à la seconde selon son inspiration (`$gps->setStrategie(new Velo())`).

### Inconvénient / Attention
- Ne vaut la peine que lorsque le programme a **plusieurs** variantes compliquées qui changent. Déployer toute cette architecture pour une "seconde stratégie" bête et méchante est une erreur ("over-engineering"), un simple `if / else` est fait pour ça.
- Le client final (l'utilisateur ou l'UI) doit connaître l'existence des modes/classes concrètes et les différences pour pouvoir charger la bonne (`new Voiture()`).
