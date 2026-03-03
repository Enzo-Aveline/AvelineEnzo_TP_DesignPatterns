# Strategy (Stratégie)

## Problème de Conception
Imaginons que tu développes une application de navigation GPS (`NavigateurGPS`). Au début, elle ne sait calculer que des itinéraires pour les voitures. Le code marche bien.

Ensuite, on te demande d'ajouter les itinéraires pour vélos. Puis pour piétons. Puis pour les transports en commun.
Si on ne fait pas attention, la classe de notre GPS va se transformer en un monstre géant rempli de `if` et `switch`.
```php
if ($mode == 'voiture') {
    // 500 lignes de code compliquées pour calculer le traget auto
} elseif ($mode == 'velo') {
    // 500 autres lignes pour le vélo...
}
```
**Problèmes :**
1. Le fichier devient immense et impossible à maintenir.
2. Chaque modification pour corriger un bug sur les vélos risque de casser sans faire exprès le code des voitures.
3. C'est quasi-impossible de travailler à plusieurs sur le même fichier.

## Solution : Extraire les "Algorithmes"
Le pattern **Strategy** permet de prendre un algorithme (ex: "calculer une route en vélo") et de l'extraire pour le mettre dans sa propre classe, appelée **Stratégie**.

L'application (`NavigateurGPS`, qu'on va appeler le **Contexte**) ne fait plus le vrai calcul elle-même. Elle délègue tout le travail à une Stratégie qui gère ça pour elle :
- Le Contexte connaît une "Interface" (un contrat) commune à toutes les Stratégies.
- L'avantage monumental, c'est de pouvoir **changer de stratégie à la volée**, pendant l'exécution du programme !

## Structure du code (Analyse de l'exemple)

### 1. L'Interface (La Stratégie abstraite : `StrategieItineraire`)
C'est le contrat. Toute stratégie doit impérativement avoir une méthode `calculerItineraire()`.
```php
interface StrategieItineraire {
    public function calculerItineraire(string $depart, string $arrivee): string;
}
```
*   **Rôle** : Rendre toutes les futures classes d'algorithmes 100% interchangeables.

### 2. Les Stratégies Concrètes (`ItineraireVoiture`, `ItineraireVelo`, ...)
Ce sont les classes séparées et découplées qui contiennent l'algorithme spécifique (le code métier concret).
```php
class ItineraireVelo implements StrategieItineraire {
    public function calculerItineraire(...) {
        return "Trajet à vélo..."; // Le VRAI code de calcul de vélo
    }
}
```
*   **Rôle** : Faire le vrai travail. Chacune est indépendante des autres.

### 3. Le Contexte (`NavigateurGPS`)
C'est notre application principale. Elle a une propriété pour mémoriser quelle stratégie elle est en train d'utiliser, et surtout, un "*Setter*" !
```php
class NavigateurGPS {
    private StrategieItineraire $strategie; // Mémorise la stratégie

    public function setStrategie(StrategieItineraire $strategie) {
        $this->strategie = $strategie; // Changement de stratégie possible !
    }

    public function lancerGuidage($depart, $arrivee) {
        // Le contexte fait aveuglément confiance à la stratégie
        return $this->strategie->calculerItineraire($depart, $arrivee);
    }
}
```
*   **Rôle** : Fournir une interface simple au client et déléguer la complexité du calcul au comportement (la stratégie) qui tourne actuellement.

## Avantages de cette approche

1.  **Délégation à la volée (Dynamisme)** : On peut remplacer l'algorithme d'un objet en cours d'exécution au runtime (ex: *Passer de la voiture au vélo en plein milieu du trajet*).
2.  **Destruction des `if/else` géants** : Le code du Contexte redevient très propre, court, et ne se préoccupe pas de la complexité de chaque façon de faire.
3.  **Principe Ouvert/Fermé (Open/Closed)** : Si on veut rajouter les voyages en montgolfière demain, on a juste à créer une nouvelle classe `ItineraireMontgolfiere implements StrategieItineraire` sans JAMAIS toucher au code de `NavigateurGPS`.

## À savoir
*   **Le Client doit être conscient des stratégies** : Pour que ça marche, c'est au code final (celui qui paramètre le GPS à la fin du fichier) d'instancier la bonne stratégie et l'injecter au Contexte. Le client doit donc comprendre la différence entre un itinéraire voiture et vélo pour choisir le bon.`new NavigateurGPS(new ItineraireVoiture())`.
*   S'il y a très peu d'algorithmes (genre juste deux algorithmes de tris de tableau) et qu'ils ne changent jamais, inutile de sortir l'artillerie lourde. Une simple fonction et un `if/else` suffisent.
