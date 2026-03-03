# Composite (Structure en Arbre) - Résumé Rapide

**But**
Composer des objets en structure d'arbre pour représenter des hiérarchies de type "partie/tout".

**Problème résolu**
- Traiter différemment un objet simple (feuille) ou un conteneur (boîte).
- Avoir un code client rempli de `if/else` et de boucles complexes seulement pour parcourir un arbre et additionner des résultats.

**La Solution**
Forcer les éléments simples (Produits) et complexes (Boîtes) à implémenter la **même interface**. Le client traite tout l'arbre de manière uniforme sans se soucier de qui est qui.

---

### Les 3 Éléments Clés (Structure)

1. **Le Composant / L'Interface** (`ElementPanier`) : *Le contrat commun à tout l'arbre (ex: posséder `getPrix()`).*
2. **La Feuille / Produit Simple** (`Produit`) : *L'objet final au bout de l'arbre qui renvoie son prix réel.*
3. **Le Composite / Nœud** (`Boite`) : *Conteneur qui boucle secrètement sur ses enfants (qui sont aussi des Composants) pour faire la somme de leurs prix.*

---

### Avantages
- **Simplicité du code client** : Le client appelle `getPrix()` sur la boîte la plus haute, et la magie récursive opère toute seule en cascade.
- **Ajout facile (Open-Closed)** : On peut rajouter un nouveau type de contenu ou conteneur très facilement.

### Inconvénient / Attention
- **Aplatissement excessif** : Trop uniformiser empêche d'avoir des règles trop spécifiques (difficile d'interdire au compilateur de mettre une "enclume" dans un "petit sachet"). Pattern restreint exclusivement aux arborescences.
