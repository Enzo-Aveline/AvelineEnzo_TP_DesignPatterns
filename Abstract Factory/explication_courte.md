# Abstract Factory - Résumé Rapide

**But**
Créer des **familles d'objets** qui fonctionnent ensemble (ex: un paiement ET son reçu de la même marque) sans que le code général n'ait besoin de connaître leurs classes exactes.

**Problème résolu**
- Éviter les mélanges incompatibles (ex: associer un reçu Carte Bleue à un paiement PayPal par erreur).
- Éviter d'éparpiller des `new PayPalPayment()` partout dans le code (couplage fort).

**La Solution**
Créer une **"Usine" (Factory)** spécialisée par famille. L'usine "PayPal" garantit qu'elle ne fabriquera QUE des outils PayPal qui sont 100% compatibles entre eux. Le code client demande juste "un paiement et un reçu" à l'usine qu'on lui a donnée.

---

### Les 4 Éléments Clés (Structure)

1. **Les Interfaces des Produits** (`Payment`, `Receipt`)
   *Le contrat de base. Ex: "Tout paiement doit avoir la méthode processPayment()".*
2. **Les Produits Concrets** (`PayPalPayment`, `PayPalReceipt`)
   *Le vrai code fonctionnel d'une marque/famille précise.*
3. **L'Interface Fabrique (Abstract Factory)** (`PaymentAbstractFactory`)
   *Le cahier des charges de l'usine. Impose de savoir fabriquer TOUTE la famille (ex: `createPayment()` ET `createReceipt()`).*
4. **Les Usines Concrètes (Concrete Factory)** (`PayPalFactory`, `CreditCardFactory`)
   *Celles qui fabriquent les vrais objets concrets, en restant fidèles à leur famille unique.*

---

### Avantages
- **Cohérence garantie** : Impossible de mélanger des objets de familles différentes.
- **Code découplé (Open/Closed)** : Le client se fiche de savoir si on utilise l'usine PayPal ou CarteBleue, il utilise les méthodes abstraites. Ajouter une usine `ApplePayFactory` ne casse rien.

### Inconvénient / Attention
- **Lourdeur** : Beaucoup de fichiers à créer. Inutile s'il n'y a pas la notion de "famille" (dans ce cas, utiliser le pattern *Factory Method* simple).
- **Rigidité des familles** : Si on veut obliger toutes les usines à créer un 3e objet (ex: système `AntiFraud`), il faut modifier l'Interface Fabrique ET toutes les Usines existantes.
