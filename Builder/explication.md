# Builder (Monteur)

## Problème de Conception
Imaginons que tu développes un site e-commerce de matériel informatique, et tu dois instancier des ordinateurs (`Ordinateur`). Au début, il s'agit juste d'un ordinateur très standardisé pour de la bureautique. Le constructeur est simple et le code marche bien.

Ensuite, on te demande d'ajouter d'autres types de configurations : une carte graphique dédiée surpuissante, du refroidissement liquide, des LED RGB, une carte Wifi, des disques durs additionnels, etc.
Si on ne fait pas attention, le constructeur de ta classe `Ordinateur` va se transformer en un monstre avec 15 arguments, dont la plupart seront nuls (ce qu'on appelle "l'anti-pattern du constructeur télescopique").
```php
class Ordinateur {
    public function __construct(
        $cpu, $ram, $stockage, $gpu = null, 
        $refroidissementLiquide = false, $ledsRGB = false, $carteWifi = false
    ) {
        // ...
    }
}

// Pour créer un PC de bureau basique, on se retrouve avec ça :
$pc = new Ordinateur('Intel i3', '8Go', '256Go', null, false, false, true);
```
**Problèmes :**
1. Le code d'initialisation devient horrible à lire et le constructeur est surchargé de paramètres `null` ou `false`.
2. L'ordre des paramètres est facile à confondre (erreur classique : inverser les booléens de RGB et de Wifi).
3. Si on ajoute une nouvelle option demain, il faut encore modifier la signature du constructeur !

## Solution : Extraire la "Construction"
Le pattern **Builder** permet d'extraire le code de construction de l'objet de sa classe mère pour le déplacer dans des objets séparés, appelés  **Builders**.

Au lieu d'appeler un constructeur géant, tu construis ton objet étape par étape, en appelant uniquement les méthodes dont tu as besoin (ex: `installerGPU()`, `ajouterLeds()`).
- L'application (le code client) ne fait plus face à un constructeur obèse. Elle délègue l'assemblage à un Builder.
- L'avantage monumental, c'est de pouvoir **réutiliser le même processus de construction pour créer des modèles très différents** (un PC Gamer surpuissant ou un petit PC de bureau en utilisant les mêmes étapes de base) !

## Structure du code (Analyse de l'exemple)

### 1. L'Interface (Le Monteur abstrait : `MonteurOrdinateur`)
C'est le contrat. Tout monteur doit impérativement déclarer les étapes de construction possibles.
```php
interface MonteurOrdinateur {
    public function installerCPU(): void;
    public function installerRAM(): void;
    public function installerStockage(): void;
    public function installerGPU(): void;
    public function ajouterLedsRGB(): void;
    public function getOrdinateur(): Ordinateur; // Méthode pour récupérer le résultat final
}
```
*   **Rôle** : Rendre tous les futurs processus de montage interchangeables et standardisés.

### 2. Les Monteurs Concrets (`MonteurPCGamer`, `MonteurPCBureau`, ...)
Ce sont les classes séparées et découplées qui implémentent les étapes de construction de façon spécifique au type de produit.
```php
class MonteurPCGamer implements MonteurOrdinateur {
    private Ordinateur $pc;

    public function __construct() {
        $this->pc = new Ordinateur(); // Objet vierge au départ
    }

    public function installerCPU(): void {
        $this->pc->ajouterComposant("Intel i9");
    }
    
    // ... implémentation des autres étapes avec du matériel haut de gamme

    public function ajouterLedsRGB(): void {
        $this->pc->ajouterComposant("Bande Leds RGB synchronisée");
    }

    public function getOrdinateur(): Ordinateur {
        return $this->pc; // On rend l'objet une fois fini
    }
}
```
*   **Rôle** : Faire le vrai travail d'assemblage étape par étape, selon un cahier des charges spécifique (ici, un PC pour jouer). Chacun est indépendant des autres.

### 3. Le Directeur (`DirecteurAssemblage`)
C'est l'architecte ou le chef d'atelier (optionnel mais très utile). Il connaît l'ordre exact dans lequel appeler les étapes pour s'assurer que le produit est bien fabriqué (les recettes toutes prêtes).
```php
class DirecteurAssemblage {
    private MonteurOrdinateur $monteur; // Mémorise le monteur actuel

    public function setMonteur(MonteurOrdinateur $monteur) {
        $this->monteur = $monteur; // Changement de monteur possible !
    }

    public function construirePCGamerComplet() {
        // Le directeur orchestre la recette complète
        $this->monteur->installerCPU();
        $this->monteur->installerRAM();
        $this->monteur->installerStockage();
        $this->monteur->installerGPU();
        $this->monteur->ajouterLedsRGB();
    }
}
```
*   **Rôle** : Fournir une interface simple au client (des recettes d'assemblage pré-définies) et déléguer la complexité de chaque montage au Builder qui tourne actuellement.

## Avantages de cette approche

1.  **Construction par étapes (Clarté)** : Fini le constructeur géant et illisible. On obéit à une construction pas à pas et on n'appelle que les étapes nécessaires.
2.  **Variations de produits (Souplesse)** : Tu peux utiliser exactement le même code dans le `DirecteurAssemblage` mais le lier à un autre Builder (ex: `MonteurPCBureau`) pour obtenir une machine totalement différente en respectant la même recette.
3.  **Principe de Responsabilité Unique (SRP)** : Le code extrêmement complexe d'assemblage est isolé dans les Builders, ce qui soulage complètement la classe de l'objet en lui-même.

## À savoir
*   **Le Client récupère le résultat chez le Builder, pas chez le Directeur** : Le directeur (qui orchestre) ne renvoie généralement pas l'objet. C'est le code final qui instancie le bon builder, le donne au directeur, déclenche la construction avec une recette, et va chercher l'objet fini directement dans le builder : `$monteur->getOrdinateur()`.
*   S'il n'y a pas vraiment d'étapes de construction compliquées et que ton objet n'a que 2 ou 3 propriétés de base, inutile de sortir l'artillerie lourde. Un simple constructeur classique suffit.
