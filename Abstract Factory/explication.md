# Abstract Factory (Fabrique Abstraite)

## Problème de Conception
Imaginons une application qui gère des paiements. Sans pattern adapté, si on doit créer non seulement des objets relatifs au traitement du paiement (`Payment`) mais aussi des objets pour la facture/le reçu (`Receipt`) qui vont avec, on se retrouve face à plusieurs problèmes :

1.  **Code lié à des familles spécifiques** : Si notre code instancie directement un `new PayPalPayment()` et un `new CreditCardReceipt()`, on risque de mélanger des objets qui ne sont pas compatibles entre eux (un paiement PayPal avec un reçu de Carte Bancaire).
2.  **Couplage Fort aux classes concrètes** : Le code complexe de l'application (le code client) dépend des classes exactes des produits. Si on veut ajouter une nouvelle famille de paiement, il faut modifier le code partout où les objets sont créés (violation du principe Open/Closed).

## Solution : L'Usine de Familles (Abstract Factory)
Le pattern **Abstract Factory** propose de regrouper la création de **familles** d'objets liés ou dépendants sans spécifier leurs classes concrètes.

Au lieu que le code client crée les objets lui-même, il va déléguer cette tâche à une "Usine" (Factory). Une usine spécifique (ex: l'usine PayPal) garantira que tous les objets qu'elle fabrique (Paiement, Reçu) fonctionnent bien ensemble et font partie de la même "famille" logique.

*Note : C'est une évolution de la **Factory Method**. La Factory Method sert à masquer la création d'**un seul** type de  produit, tandis que l'Abstract Factory est conçue pour créer **plusieurs produits différents qui fonctionnent ensemble**.*

## Structure du code (Analyse de l'exemple)

### 1. Les Interfaces des Produits (`Payment`, `Receipt`)
Ce sont les contrats que tous les produits de toutes les familles doivent respecter.
```php
interface Payment {
    public function processPayment($amount);
}
interface Receipt {
    public function printReceipt($amount);
}
```
*   **Rôle** : S'assurer que le code de l'application peut utiliser n'importe quel paiement ou reçu de la même manière, de façon abstraite, peu importe s'il s'agit de PayPal ou de la Carte Bancaire.

### 2. Les Produits Concrets (ex: `PayPalPayment`, `PayPalReceipt`)
Ce sont les implémentations concrètes des produits pour une famille donnée.
*   **Rôle** : Définir le comportement spécifique d'un produit (ex: *comment* traiter un paiement PayPal ou *comment* imprimer son propre ticket).

### 3. L'Interface Fabrique Abstraite (`PaymentAbstractFactory`)
C'est le contrat au niveau des usines. Elle déclare obligatoirement une méthode de création pour **chaque** type de produit (paiement et reçu) afin de former une famille complète.
```php
interface PaymentAbstractFactory {
    public function createPayment(): Payment;
    public function createReceipt(): Receipt;
}
```
*   **Rôle** : Obliger toute usine concrète à fournir la panoplie complète des produits de la famille.

### 4. Les Usines Concrètes (`CreditCardFactory`, `PayPalFactory`)
Ce sont les classes qui implémentent la fabrique abstraite pour fabriquer et retourner **uniquement** les objets d'une seule famille.
```php
class PayPalFactory implements PaymentAbstractFactory {
    public function createPayment(): Payment { return new PayPalPayment(); }
    public function createReceipt(): Receipt { return new PayPalReceipt(); }
}
```
*   **Rôle** : Construire les bons produits concrets. La `PayPalFactory` ne renverra jamais un objet de type Carte Bancaire, ce qui empêche les mélanges accidentels au sein de l'application.

*(Dans notre code, on a ajouté un `FactoryProvider::getFactory($type)` pour récupérer dynamiquement la bonne usine à partir d'une simple chaîne de caractère, ce qui ressemble beaucoup à ton approche du Factory Method, mais appliqué sur toute une famille d'objets !)*

## Avantages de cette approche

1.  **Cohérence garantie** : Vous êtes certain que les produits d'une même fabrique sont compatibles entre eux. Si vous utilisez l'usine PayPal, vous obtenez exclusivement les outils orientés PayPal.
2.  **Découplage total (Couplage lâche)** : Le code central de votre application manipule uniquement des interfaces (`PaymentAbstractFactory`, `Payment`, `Receipt`). Il n'a aucune idée des classes concrètes (côté PayPal ou CreditCard) qui font réellement le travail.
3.  **Principe Ouvert/Fermé (Open/Closed)** : On peut introduire de nouvelles variantes de produits (ex: ajouter une usine `ApplePayFactory`) en créant simplement ses propres classes, sans casser le code existant qui gère les paiements en général.

## À savoir
*   **Complexité accrue** : Ce pattern introduit beaucoup de nouvelles interfaces et de classes. Il ne faut l'utiliser que si on a réellement besoin de gérer des *familles* (plusieurs objets différents qui doivent impérativement cohabiter ensemble). S'il n'y a qu'un seul objet à créer, on reste sur une simple **Factory Method**.
*   **Lourdeur pour ajouter de nouveaux types d'objets** : Si un jour tu décides que chaque paiement nécessite aussi un système anti-fraude (`AntiFraud` interface), il faudra modifier le contrat `PaymentAbstractFactory` et ensuite aller mettre à jour **toutes** tes classes d'usines existantes (`PayPalFactory`, `CreditCardFactory`...) pour implémenter ce nouveau composant. L'ajout d'une nouvelle "catégorie" de produit est donc lourd.
