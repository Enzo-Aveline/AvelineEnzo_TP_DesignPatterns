# Le Pattern Decorator

## 🎯 But
Le pattern **Decorator** (Décorateur) est un pattern structurel qui permet d'ajouter dynamiquement de nouveaux comportements ou propriétés à un objet existant sans modifier sa structure profonde. Il offre une alternative flexible à l'héritage pour étendre des fonctionnalités.

## 😟 Le Problème
Imaginez que vous gérez les commandes d'un café. Vous avez une classe `CafeSimple` et une autre `Deca`.
Les clients peuvent ajouter des ingrédients : Lait, Caramel, Chantilly...
Si vous utilisez l'héritage pour chaque combinaison, vous allez devoir créer :
- `CafeLait`
- `CafeCaramel`
- `CafeLaitCaramel`
- `DecaLait`
- `DecaLaitChantilly`
... C'est une **explosion de classes** impossible à maintenir !

## ✅ La Solution
Le pattern Decorator propose de considérer les ingrédients comme des "enveloppes" (wrappers).
- On commence par l'objet de base (le Café).
- On l'enveloppe dans un ingrédient (Lait).
- On enveloppe le résultat dans un autre ingrédient (Caramel).
Chaque ingrédient modifie le résultat (ajoute son prix et son nom) et appelle l'objet qu'il enveloppe.

Exemple : `Chantilly( Caramel( Lait( CafeSimple ) ) )`

## 🏗️ Structure
1.  **Component** (`Boisson`) : L'interface commune. Tous (cafés et ingrédients) doivent être des `Boisson` pour pouvoir s'emboîter.
2.  **Concrete Class** (`CafeSimple`, `Deca`) : L'objet de base. C'est le cœur de l'oignon, la boisson initiale sans rien d'autre.
3.  **Base Decorator** (`BoissonDecorator`) : Classe abstraite qui contient une propriété `$boisson` (le suivant dans la chaîne) c'est cette classe qui va contenir le prix et la description de la boisson.
4.  **Concrete Decorators** (`Lait`, `Caramel`, `Chantilly`) : Ce sont les ingrédients.
    - Dans `cout()`, ils appellent `parent::cout()` (le prix de ce qu'ils enveloppent) et ajoutent leur propre prix.
    - Dans `getDescription()`, ils appellent la description précédente et ajoutent leur nom.

## 👍 Avantages
- **Flexibilité** : On compose la boisson "à la carte" au moment de la commande (runtime).
- **Principe Ouverture/Fermeture** : On peut créer de nouveaux ingrédients (`Chocolat`, `Vanille`) sans jamais toucher au code du `CafeSimple` ou des autres ingrédients.
- **Simplicité** : Chaque classe ne gère que son petit bout de logique (Le `Lait` ne connait que le prix du Lait).

## 👎 Inconvénients
- Beaucoup de petits objets en mémoire.
- L'instanciation peut devenir verbeuse (`new A(new B(new C(...)))`) si on n'utilise pas un autre pattern pour l'aider (comme une Factory ou un Builder).
