# Factory Method (Basé sur l'exemple Payment)

## Problème
Imaginez que vous développez un système de paiement. Aujourd'hui, vous ne gérez que les cartes de crédit. Demain, vous devrez peut-être ajouter PayPal, virement bancaire, Bitcoin, etc.

Si votre code principal instancie directement les classes de paiement (ex: `new CreditCardPayment()`), vous allez devoir modifier ce code à chaque fois que vous ajoutez un nouveau moyen de paiement. Votre code devient rigide et difficile à maintenir car il dépend des classes concrètes pour chaque type de paiement.

## Solution : La Fabrique (Factory)
Au lieu de créer les objets directement avec `new` dans le code client avec plein de if else degeulasse, on délègue cette responsabilité à une classe spécialisée : la **Factory** (`PaymentFactory`).

Dans l'exemple `exemple.php`, la méthode `PaymentFactory::createPayment` agit comme un point central pour "fabriquer" et utiliser le bon service de paiement en fonction d'un simple paramètre (une chaîne de caractères comme `'CreditCard'` ou `'PayPal'`).

## Structure du code (Analyse de l'exemple)

L'exemple fourni met en œuvre une variation du pattern (souvent appelée **Simple Factory** ou **Static Factory**) :

### 1. L'Interface Commune (The Product Interface)
C'est le contrat que tous les paiements doivent respecter. Grace au polymorphisme, on peut appeler la même méthode sur des objets de classes différentes.
```php
interface Payment
{
    public function processPayment($amount);
}
```
*   **Rôle** : Garantit que peu importe le type de paiement créé, on pourra toujours appeler la méthode `processPayment`.

### 2. Les Produits Concrets (Concrete Products)
Ce sont les implémentations réelles des différents moyens de paiement.
```php
class CreditCardPayment implements Payment { ... }
class PayPalPayment implements Payment { ... }
```
*   **Rôle** : Ils contiennent la logique spécifique à chaque moyen de paiement (ex: API carte bancaire vs API PayPal).

### 3. La Fabrique (The Creator)
C'est la classe qui contient la logique de création.
```php
class PaymentFactory
{
    public static function createPayment($type, $amount)
    {
        // 1. Détermination dynamique de la classe à instancier
        $classname = $type . "Payment";
        
        // 2. Instanciation (Le "new" est caché ici)
        if (class_exists($classname)) {
            $payment = new $classname();
            $payment->processPayment($amount);
        } else {
             throw new Exception("Type de paiement inconnu");
        }
    }
}
```
*   **Rôle** : Elle encapsule la complexité de la création. Le client n'a pas besoin de savoir qu'il existe une classe `CreditCardPayment`, il demande juste un paiement de type `'CreditCard'`.

## Avantages de cette approche

1.  **Découplage** : Le code qui appelle `PaymentFactory::createPayment` ne dépend pas des classes `CreditCardPayment` ou `PayPalPayment`. Il ne connaît que l'interface `Payment` (implicitement).
2.  **Extensibilité (Open/Closed Principle)** : Pour ajouter un paiement par "Bitcoin", il suffit de créer une classe `BitcoinPayment` implémentant `Payment`. Si la logique de nommage est respectée, la Factory fonctionnera sans même avoir besoin d'être modifiée (grâce à l'instanciation dynamique `$type . "Payment"`).
3.  **Code plus propre** : On évite de dupliquer les `if ($type == 'paypal') { new ... }` partout dans l'application.

## Limitations de l'exemple
L'utilisation de la concaténation de chaînes (`$type . "Payment"`) est une astuce PHP pratique mais qui nécessite une convention de nommage stricte. Dans une implémentation plus "pure" du pattern Factory Method classique, on utiliserait souvent un `switch` explicite pour plus de sécurité.
