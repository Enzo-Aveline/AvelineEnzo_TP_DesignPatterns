# Decorator (Décorateur) - Résumé Rapide

**But**
Ajouter dynamiquement de nouveaux comportements ou propriétés à un objet sans modifier son code interne et sans utiliser l'héritage.

**Problème résolu**
- L'explosion combinatoire des sous-classes : si on utilise l'héritage, 3 ingrédients de café possibles font qu'on doit créer 8 classes (`CafeLait`, `CafeLaitCaramel`, etc).

**La Solution**
Utiliser des objets "enveloppes" (wrappers). On enveloppe le café de base dans un Ingrédient Lait, puis on enveloppe le tout dans l'Ingrédient Caramel : `Caramel(Lait(Cafe))`. Chaque ingrédient ajoute son comportement/prix à l'objet qu'il enveloppe.

---

### Les 4 Éléments Clés (Structure)

1. **Composant** (`Boisson`) : *L'interface commune pour que le café de base et les ingrédients puissent s'emboîter comme des poupées russes.*
2. **Classe Concrète** (`CafeSimple`) : *L'objet originel, le centre absolu de l'enveloppe, sans aucune poudre dessus.*
3. **Base Decorator** (`BoissonDecorator`) : *Classe abstraite qui mémorise la référence vers l'enveloppe en-dessous.*
4. **Decorators Concrets** (`Lait`, `Caramel`) : *Les ajouts, qui font `parent::cout()` (le prix d'en-dessous) + leur propre prix.*

---

### Avantages
- **Flexibilité à l'exécution** : On compose l'objet à la volée, au runtime, selon la commande exacte.
- **Principe Ouvert/Fermé** : Créer un nouvel ingrédient `Chocolat` n'affecte ni le café de base, ni les autres décorateurs.

### Inconvénient / Attention
- **Verbosité à la création** : Instanciations parfois lourdes à taper (`new A(new B(new C()))`), souvent cachées derrière un pattern Factory.
- **Débuggage complexe** : Un appel passe par beaucoup de petites couches "fantômes", et on perd facilement de vue ce qui fait quoi.
