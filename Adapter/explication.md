# Adapter (Adaptateur)

## Problème de Conception
Quand on doit intégrer un élément externe (un appareil étranger, une librairie tierce, une API), on n'a généralement pas de prise sur son fonctionnement interne, le type d'appels qu'il demande ou son "voltage" de fonctionnement.

Pour que tout fonctionne correctement avec notre propre système (qui a ses propres normes), il faut adapter la connexion. Mais si on "bricole" notre propre système (en coupant les fils du mur pour les brancher directement sur l'appareil étranger), on risque :
1. De **créer des bugs** ou des courts-circuits partout dans la maison.
2. De rendre la maintenance infernale (si demain on achète un autre appareil, il faut encore tout recâbler).

*L'exemple du quotidien* : Tu arrives avec ton chargeur de téléphone américain (qui fonctionne en 110V avec des fiches plates) dans une maison en France (qui fonctionne en 220V avec des fiches rondes).

## Solution : Mettre en place un Adaptateur
Pour résoudre ça, on achète un **Adaptateur de voyage**. Le principe est de créer un objet intermédiaire qui va "traduire" la demande :
- Il présente une fiche française de 220V (ce que notre maison s'attend à voir).
- Dès qu'il reçoit du courant, il le transforme en 110V.
- Et il le transmet à l'appareil américain qui est secrètement branché de l'autre côté.

Ton système (la maison) continue de fonctionner exactement comme elle a l'habitude de le faire, comme s'il ne s'était rien passé !

## Structure du code (Analyse de l'exemple)

### 1. L'Interface cible (`PriseFrancaise`)
C'est le contrat de base que ton application comprend et a l'habitude d'utiliser.
```php
interface PriseFrancaise {
    public function brancherSur220V(): void;
}
```
*   **Rôle** : Définir clairement comment l'application principale s'attend à interagir (la prise murale française).

### 2. Le Service Externe ou "Adaptee" (`ChargeurAmericain`)
C'est l'appareil ou la librairie tierce achetée à l'étranger. Tu ne peux pas modifier son code source, et ses méthodes/normes sont très différentes.
```php
class ChargeurAmericain {
    public function brancherSur110V(): void { /* ... */ }
}
```

### 3. L'Adaptateur (`AdaptateurVoyage`)
C'est la fameuse classe "Traductrice". Elle implémente l'interface de notre application (`PriseFrancaise`) pour pouvoir être branchée dans le mur, mais à l'intérieur d'elle-même, elle encapsule l'appareil étranger (`ChargeurAmericain`).
```php
class AdaptateurVoyage implements PriseFrancaise {
    private $chargeurUS;
    
    public function brancherSur220V(): void {
        // L'adaptateur TRADUIT du format attendu par la maison (220V) 
        // vers ce dont le service externe a besoin (110V)
        echo "Conversion 220V -> 110V...";
        
        // Et il appelle la bonne méthode externe !
        $this->chargeurUS->brancherSur110V();
    }
}
```

## Avantages de cette approche

1.  **Principe de responsabilité unique (SRP)** : La "traduction" des données (la conversion de voltage, le changement de méthode) est isolée dans l'adaptateur et ne pollue plus le code principal du mur ou de la maison.
2.  **Principe Ouvert/Fermé (OCP)** : Tu peux ajouter de nouveaux adaptateurs (ex: un adaptateur pour l'Angleterre, pour le Japon) sans avoir à modifier une seule ligne du code de ta maison ou des appareils.
3.  **Migration facilitée** : Le pattern excelle massivement quand on fait appel à des API externes, librairies tierces, ou autres bibliothèques qui n'ont pas la même logique que ton propre code.

## À savoir
*   **Multiplier les boîtes** : L'inconvénient principal, c'est que ça multiplie le nombre d'interfaces et de classes "tampons". Si le composant n'est pas "externe mais intouchable", mais qu'il fait partie intégrante de ton code de tous les jours, c'est parfois plus simple et moins lourd de juste mettre sa classe à jour plutôt que de rajouter un adaptateur au milieu.
*   **Lien avec d'autres patterns** : L'Adaptateur s'occupe de faire collaborer deux trucs dont les signatures sont incompatibles *après coup*. Contrairement au **Decorator** (Vu sur le café) qui essaie d'ajouter une propriété à un objet sans en changer la nature/l'interface.
*   **Cas d'usage courant** : L'architecture Hexagonale. Mais c'est aussi le principe fondamental de tous les ORMs de bases de données (Doctrine, Eloquent) : tu manipules des requêtes en langage PHP, l'adaptateur se charge de tout convertir en langage SQL / JSON pour aller taper sur la bonne base de données.
