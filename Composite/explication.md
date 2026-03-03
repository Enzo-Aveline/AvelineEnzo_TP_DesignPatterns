# 📦 Composite (Structure en Arbre)

## 🎯 Problème de Conception
Imaginons qu'une application de e-commerce doive calculer le prix total du contenu d'un colis d'expédition. 
Un colis d'expédition n'est pas plat. Il peut contenir :
- Des **Produits simples** (un livre, un téléphone...).
- Des **Petites Boîtes**, qui contiennent elles-mêmes des produits simples ou encore d'autres toutes petites boîtes.

Sans le pattern Composite, le code client de ton application devrait parcourir manuellement chaque élément. Il devrait vérifier avec des `if/else` s'il s'agit d'une boîte ou d'un produit. Si c'est une boîte, il doit faire une nouvelle boucle complexe pour voir ce qu'il y a dedans. C'est l'enfer à maintenir, surtout si l'arbre est très profond (des boîtes dans des boîtes dans des boîtes).

## 🧠 Solution : L'Arbre uniformisé

En gros quand on ajoute quelque chode au panier, on peut soit ajouter un produit, soit ajouter une boîte parce ajoutelement implemente la meme interface ElementPanier.

Le pattern **Composite** permet de composer des objets dans des structures en **arbre** :
- **La Feuille (Leaf)** : C'est le produit fini. Il n'a pas d'enfant.
- **Le Composite/Nœud** : C'est la boîte. Elle contient des feuilles ou d'autres composites.

Le génie de ce pattern, c'est de forcer le produit simple et la boîte à avoir **une interface commune** (ici, une même méthode `getPrix()`). 
Ainsi, le code n'a plus besoin de tester s'il manipule un gros carton ou juste un livre : il demande le `getPrix()` de la même manière à tout le monde. La boîte, elle, s'occupe de relayer et additionner la demande à tous ses enfants.

## 🏗 Structure du code (Analyse de l'exemple)

### 1. L'Interface (Le Composant : `ElementPanier`)
C'est le contrat qui déclare ce que tout nœud de l'arbre doit savoir faire, qu'importe qu'il soit un objet simple ou complexe.
```php
interface ElementPanier {
    public function getPrix(): float;
}
```
*   **Rôle** : Uniformiser l'accès. La boîte principale et un simple produit répondront tous les deux à cet appel.

### 2. La Feuille / Le Produit Simple (`Produit`)
C'est l'élément final de l'arbre.
```php
class Produit implements ElementPanier {
    // ... son prix de base
    public function getPrix(): float {
        return $this->prix; // Il retourne son véritable prix
    }
}
```
*   **Rôle** : Réaliser le vrai traitement final qui stoppe la récursivité.

### 3. Le Composite (`Boite`)
C'est l'élément complexe qui contient une liste de composants (les enfants), qui peuvent eux-mêmes être des Boîtes ou des Produits.
```php
class Boite implements ElementPanier {
    private $elements = []; // Peut contenir des Produit OU des Boite !
    
    //... (méthode de type $this->elements[] = $element) ...

    public function getPrix(): float {
        $total = 0;
        foreach ($this->elements as $element) {
            $total += $element->getPrix(); // Il délègue le travail à ses enfants !
        }
        return $total;
    }
}
```
*   **Rôle** : Parcourir ses enfants, accumuler leurs propres résultats et les renvoyer au niveau supérieur.

## 📈 Avantages de cette approche

1.  **Simplicité incroyable pour ton code central** : Ton code `checkout` (le code client) a juste besoin d'appeler `$grandCarton->getPrix()`. Il ne se soucie pas de savoir si c'est un produit à nu ou une boîte contenant 40 autres boîtes. L'arbre entier se calcule de lui-même.
2.  **Facilité d'extension** : Tu peux rajouter d'autres composants complexes (ex: un `SachetPlastique`) ou d'autres produits en respectant juste l'interface, sans avoir à casser l'existant. C'est l'application directe du principe **Ouvert/Fermé (Open/Closed)**.

## ⚠️ À savoir
*   **Applicable uniquement pour des arbres** : Le pattern n'a de sens **que** si ton modèle de données possède naturellement une structure en forme d'arbre/arborescence où "une chose contient d'autres choses de la même classe parente". C'est pour ça qu'on l'utilise souvent pour les systèmes de Fichiers/Dossiers, ou pour des listes d'élèments graphiques (Un conteneur d'interface qui contient des boutons, d'autres conteneurs, etc).
*   **Aplatissement trop généralisé** : En donnant une interface commune à tous les objets, le compilateur ne sait pas faire la différence entre une feuille et un composite. Il devient parfois complexe d'imposer des règles métier comme : *"Une petite boîte ne peut pas contenir un très gros produit"*.
