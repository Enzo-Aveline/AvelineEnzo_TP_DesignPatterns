# Builder (Monteur) - Résumé Rapide

**But**
Permettre la construction d'objets complexes étape par étape. Séparer la construction d'un objet de sa représentation finale.

**Problème résolu**
- Le fameux **"constructeur télescopique"** avec 15 arguments presque tous optionnels (ex: `new Ordinateur('i3', '8Go', null, false, false, true)`).
- Le code de création est illisible, facile à casser et très rigide si on veut ajouter de nouvelles options.

**La Solution**
Extraire la logique de construction de la classe principale. Le client va "monter" son objet petit à petit en appelant des méthodes claires (ex: `installerCPU()`, `ajouterLeds()`) auprès d'une classe spéciale appelée **Builder**.

---

### Les 4 Éléments Clés (Structure)

1. **L'Interface / Le Builder abstrait** (`BuilderOrdinateur`)
   *Le contrat qui liste toutes les étapes de montage possibles (ex: méthode `installerRAM()`).*
2. **Les Builders Concrets** (`BuilderPCGamer`, `BuilderPCBureau`)
   *Ceux qui implémentent les étapes de manière spécifique (le builder gamer mettra 32Go de RAM, le bureau 8Go).*
3. **Le Produit final** (`Ordinateur`)
   *L'objet complexe qu'on est en train d'assembler.*
4. **Le Directeur** *(optionnel)* (`DirecteurAssemblage`)
   *Une classe qui propose des "recettes toutes prêtes". Elle appelle les étapes de construction dans le bon ordre à la place du client.*

---

### Avantages
- **Lisibilité** : Fini les constructeurs avec 10 paramètres incompréhensibles.
- **Souplesse** : Mêmes algorithmes/recettes de construction (Directeur) mais résultats différents selon le Builder choisi.
- **Étape par étape** : On ne renvoie le produit final que quand il est 100% terminé.

### Inconvénient / Attention
- **Complexité ajoutée** : Beaucoup de nouvelles classes pour remplacer un simple constructeur. À éviter pour des objets simples (2-3 attributs).
- **Variante populaire (Fluent Builder)** : Très souvent, on n'utilise pas le "Directeur", mais on renvoie `$this` à la fin de chaque méthode du Builder pour pouvoir "chaîner" les méthodes : `$builder->setCPU('i9')->setRAM('32Go')->build();`
