# Command (Commande) - Résumé Rapide

**But**
Transformer une action (requête) en un objet autonome contenant toutes les informations sur cette requête.

**Problème résolu**
- Couplage fort entre l'interface utilisateur (bouton) et le code métier (lampe).
- Impossibilité d'annuler une action (Ctrl+Z) ou de faire un historique si ce sont de simples appels de fonctions directes.

**La Solution**
Créer une classe dédiée par commande (ex: `CommandeAllumerLampe`). L'interface ne déclenche pas le code final, elle se contente d'exécuter l'objet Commande qu'on lui a attribué.

---

### Les 4 Éléments Clés (Structure)

1. **L'Interface** (`Commande`) : *Définit les méthodes `executer()` et `annuler()` obligatoires.*
2. **Le Récepteur** (`Lampe`) : *L'objet final qui fait le vrai travail (allumer, éteindre).*
3. **Les Commandes Concrètes** (`CommandeAllumerLampe`) : *Lien entre l'action abstraite et le récepteur.*
4. **L'Invocateur** (`Telecommande`) : *Celui qui demande à la commande de s'exécuter.*

---

### Avantages
- **Découplage parfait** : L'émetteur (UI) ne connait pas le récepteur (métier).
- **Historique et Undo (Ctrl+Z)** : Les objets commandes peuvent être stockés en tableau et annulés un par un (grâce à la méthode `annuler()`).

### Inconvénient / Attention
- **Surplus de fichiers** : Il faut créer une classe par action (ex: 50 fichiers si l'application a 50 boutons d'actions différentes).
