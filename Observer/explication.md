# 📡 Observer (Basé sur l'exemple YouTube)

```markdown
## 🎯 Problème de Conception
Imaginons une classe `ChaineYoutube` qui doit avertir des objets `Utilisateur` lors d'un changement d'état (nouvelle vidéo). Sans pattern adapté, on se retrouve face à deux impasses :

1.  **Le Polling (Vérification active)** : Les objets `Utilisateur` interrogent la `ChaineYoutube` à intervalle régulier pour détecter une mise à jour. Cette approche est coûteuse en ressources et crée une latence inutile.
2.  **Le Couplage Fort** : Si la `ChaineYoutube` gère elle-même l'envoi des notifications, elle doit connaître concrètement chaque classe dépendante (ex: `$user->notifier()`, `$logger->log()`). Toute modification ou ajout d'un nouveau type d'observateur oblige à modifier le code source de `ChaineYoutube`, violant le principe **Open/Closed** (le code doit être ouvert à l'extension mais fermé à la modification).
```

## 🧠 Solution : L'Abonnement (Observer)
Le pattern **Observer** propose un mécanisme d'abonnement simple : "Ne m'appelez pas, je vous appellerai".

Au lieu que les fans vérifient la chaîne, c'est la chaîne (`Sujet`) qui garde une liste de tous les intéressés (`Observateurs`) et les prévient automatiquement (`notifier`) dès qu'il se passe quelque chose (`publierVideo`).

et au lieu d'etre lié a une classe concrète, on utilise des interfaces pour que le code soit plus flexible. C'est de l'inversion de dépendance.

## 🏗 Structure du code (Analyse de l'exemple)

### 1. Le Sujet (L'Interface Observable)
C'est le contrat que l'objet observé doit respecter pour gérer sa liste de diffusion.
```php
interface Sujet {
    public function abonner(Observateur $obs);
    public function desabonner(Observateur $obs);
    public function notifier();
}
```
*   **Rôle** : Définit comment on ajoute ou retire quelqu'un de la liste des notifiés.

### 2. L'Observateur (L'Interface Abonné)
C'est le contrat que tous les abonnés doivent respecter pour pouvoir être prévenus.
```php
interface Observateur {
    public function actualiser(Sujet $message);
}
```
*   **Rôle** : Garantit que la chaîne YouTube pourra appeler la même méthode `actualiser()` sur n'importe quel abonné, qu'il soit un humain, une application mobile ou un bot.

### 3. Le Sujet Concret (`ChaineYoutube`)
C'est la classe qui contient la vraie logique et l'état intéressant (la dernière vidéo).
```php
class ChaineYoutube implements Sujet {
    private $abonnes = []; // LA LISTE IMPORTANTE

    public function publierVideo($titre) {
        $this->notifier(); // Prévient tout le monde après l'action
    }
    // ...
}
```
*   **Rôle** : Elle stocke la liste des abonnés et boucle dessus ("foreach") pour appeler leur méthode `actualiser()` quand une vidéo sort.

### 4. L'Observateur Concret (`Abonne`)
C'est la classe qui reçoit la notification.
```php
class Abonne implements Observateur {
    public function actualiser(Sujet $chaine) {
        // Logique de réaction (afficher un message, envoyer un mail...)
        echo "J'ai vu la nouvelle vidéo !";
    }
}
```
*   **Rôle** : Définit ce que l'abonné fait concrètement quand il reçoit l'info.

si on veut ajouter un nouvel observateur par exemple un bot qui envoie un message sur discord, on n'a pas besoin de modifier la classe `ChaineYoutube`.
on ajoute simplement une nouvelle classe qui implémente l'interface `Observateur`.

## 📈 Avantages de cette approche

1.  **Découplage total** : La `ChaineYoutube` ne connaît pas le code de `Abonne`. Elle sait juste qu'il a une méthode `actualiser`. On peut modifier `Abonne` sans toucher à `ChaineYoutube`.
2.  **Flexibilité** : On peut ajouter de nouveaux types d'observateurs (ex: `GoogleBot`, `ApplicationIphone`) sans changer une seule ligne de la classe `ChaineYoutube`.
3.  **Dynamisme** : Les relations se font à l'exécution (`$chaine->abonner($user)`). Un utilisateur peut se désabonner à tout moment.

## ⚠️ À savoir
*   **L'ordre n'est pas garanti** : Si vous avez 1000 abonnés, vous ne savez pas qui sera notifié en premier (ça dépend de l'ordre dans le tableau).
*   **Attention aux boucles** : Faire attention a ne pas modifier le sujet dans la méthode `actualiser` de l'observateur. sinon on peut créer une boucle infinie.
