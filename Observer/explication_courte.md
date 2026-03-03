# Observer (Observateur) - Résumé Rapide

**But**
Définir un mécanisme d'abonnement pour qu'un objet puisse notifier automatiquement plein d'autres objets intéressés quand son état change ("Ne m'appelez pas, je vous appellerai").

**Problème résolu**
- Le "Polling" (les requêtes en boucle d'un composant pour vérifier si la cible a changé d'état : perte de performances).
- Le couplage fort de la cible si elle déclenche d'elle-même des actions vers les composants extérieurs en connaissant leurs vraies classes.

**La Solution**
La cible (le Sujet) maintient une liste à qui elle doit notifier. Lorsqu'il y a du nouveau, le Sujet parcourt ce tableau et appelle une unique méthode convenue à l'avance (`actualiser()`) pour tout le monde sans avoir à les différencier.

---

### Les 4 Éléments Clés (Structure)

1. **Le Sujet** (`Sujet`) : *L'interface qui impose d'avoir `abonner()`, `desabonner()` et `notifier()` au "Youtuber".*
2. **L'Observateur** (`Observateur`) : *L'interface qui force les abonnés à posséder la méthode universelle `actualiser()`.*
3. **Sujet Concret** (`ChaineYoutube`) : *Celui qui possède l'information maîtresse, gère concrètement la liste des abonnés, et fait la boucle "foreach" de notification.*
4. **Observateur Concret** (`Abonne`) : *Abonnés qui répondent à `actualiser()` (ici avec un code pour envoyer un mail, logger un message, envoyer un SMS).*

---

### Avantages
- **Extensibilité absolue (Inversion de dépendance)** : On peut rajouter 1000 nouveaux types d'abonnés (Bots, Scrapers, Mobiles), le Youtuber n'est jamais modifié.
- **Connexions dynamiques** : Possibilité de s'abonner / se désabonner pendant l'exécution ("runtime").

### Inconvénient / Attention
- **Ordre non garanti** : On ne prévoit jamais l'ordre absolu dans lequel les observateurs reçoivent la notification.
- **Boucles Infinies** : Danger absolu si la réaction (`actualiser`) d'un Observateur modifie le Sujet, forçant le Sujet à redéclencher une notification qui rebouclera.
