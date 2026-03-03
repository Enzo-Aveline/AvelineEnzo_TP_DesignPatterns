# Factory Method - Résumé Rapide

*Note : La version détaillée dans ce TP est une approche "Simple Factory" ou "Static Factory".*

**But**
Déléguer la création d'objets à une méthode ou une classe dédiée plutôt que d'utiliser directement le mot-clé `new` partout dans le code.

**Problème résolu**
- Modification en cascade du code à chaque fois qu'un nouveau type de classe enfant est ajouté (ex: un nouveau moyen de paiement).
- Code très moche car pollué de `if ($type == 'paypal') { new PayPal() }` un peu partout.

**La Solution**
Créer une "Usine" (`Factory`) qui prend un type de demande de l'utilisateur, et qui se charge d'instancier et renvoyer le bon objet prêt à l'emploi. Le code client envoie un signal String à la Factory de manière propre.

---

### Les 3 Éléments Clés (Structure)

1. **L'Interface Commune** (`Payment`) : *Contrat qui garantit au client que la factory renverra un objet exploitable (qui contient `processPayment()`).*
2. **Les Produits Concrets** (`CreditCardPayment`, `PayPalPayment`) : *Les véritables classes qui effectuent les actions réelles.*
3. **La Fabrique** (`PaymentFactory`) : *La classe contenant la logique d'instanciation (souvent avec un `switch` ou de l'injection/concaténation dynamique sur les chaînes de caractères).*

---

### Avantages
- **Centralisation** : La logique compliquée de création vit à un seul et unique endroit.
- **Couplage Lâche** : Le code client dépend uniquement de la Factory et de l'Interface, pas des noms exacts des classes de paiement.

### Inconvénient / Attention
- **Factory Obèse** : Si vous avez 200 objets différents à créer, le `switch` ou la logique interne de l'usine devient monstrueuse, difficile à maintenir. Moment propice pour utiliser l'Abstract Factory ou le pattern total polymorphique de Factory Method.
