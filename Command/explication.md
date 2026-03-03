# Command (Commande)

## Problème de Conception
Imagine que tu développes une télécommande universelle pour une maison connectée (Smart Home). 
Au départ, y a juste un bouton "On/Off" pour allumer une lampe. Tu écris le code d'allumage directement dans le code du bouton (`Bouton::onClick()`).

```php
// Mauvais exemple : La télécommande sait TOUT sur les appareils de la maison.
class Bouton {
    public function appuyer() {
        if ($this->appareil == 'television') { $television->allumer(); }
        elseif ($this->appareil == 'lumiere') { $ampoule->mettreCourant(); }
        // ... Des milliers de conditions pour le four, la clim, etc.
    }
}
```
**Problèmes :**
1. **Couplage très fort** : Le code de l'interface (la télécommande, les boutons) est pollué par le code métier de TOUS les appareils de la maison. C'est horrible à maintenir.
2. **Historique impossible** : Comment faire un bouton *Ctrl+Z (Undo)* pour "Annuler la dernière action de la maison" si les actions sont de simples appels d'un bout à l'autre de l'application ?

## Solution : L'Action devient un Objet
Le pattern **Command** permet de prendre une action (comme "allumer la lumière") et de la transformer en un **objet autonome**.

Au lieu que la télécommande allume la lampe, la télécommande exécute un objet `CommandeAllumerLampe`.
1. La commande est très simple : elle sait **qui** elle contrôle (la lampe) et **quoi** faire (allumer).
2. L'interface (la télécommande ou *l'Invocateur*) n'a **aucune idée** de ce qui va se passer. Elle sait juste qu'elle demande à une commande de s'exécuter.
3. Chaque objet "Commande" peut posséder une méthode `annuler()` qui fait l'inverse de son action ! Comme ce sont des objets, on peut les stocker dans une liste et faire *Ctrl+Z* sur l'historique !

## Structure du code (Analyse de l'exemple)

### 1. L'Interface (`Commande`)
Le contrat pour toutes les actions possibles.
```php
interface Commande {
    public function executer(): void;
    public function annuler(): void;
}
```
*   **Rôle** : Rendre la télécommande indépendante et permettre à toutes les actions de marcher de la même manière (le polymorphisme !).

### 2. Le Récepteur (Receiver : `Lampe`)
C'est l'objet métier de base. Celui qui prend les vrais coups.
*   **Rôle** : Savoir comment se passe l'allumage en vrai (gérer l'électricité, etc.).

### 3. Les Commandes Concrètes (`CommandeAllumerLampe`)
C'est le facteur de liaison. Elle stocke le "Récepteur" (la lampe) dans son moteur.
```php
class CommandeAllumerLampe implements Commande {
    private Lampe $lampe; // Lien avec la lampe réelle
    
    public function executer() { $this->lampe->allumer(); }
    public function annuler() { $this->lampe->eteindre(); } // L'action inverse !
}
```
*   **Rôle** : Transformer un appel `executer()` abstrait, en appel `allumer()` d'une classe très concrète (`Lampe`).

### 4. L'Invocateur (`Telecommande`)
C'est l'élément graphique de l'application (le bouton, l'icône de l'app mobile, le raccourci clavier...).
```php
class Telecommande {
    private Commande $commande;

    public function appuyerBouton() {
        $this->commande->executer(); // Il n'a absolument aucune idée de ce qu'il vient de déclencher !
    }
}
```
*   **Rôle** : Recevoir les clics utilisateurs et les repousser vers l'objet Commande lié en ce moment au bouton.

## Avantages de cette approche

1.  **Découplage parfait** : L'objet qui invoque l'opération (`Telecommande`) ne connaît pas l'objet qui accomplit réellement l'opération (`Lampe`).
2.  **L'historique et le "Undo / Redo" (Ctrl+Z)** : Comme chaque action est devenue un petit objet, on peut les mettre en tableau (un historique). Pour annuler, on dépile le tableau et on appelle la méthode `annuler()`.
3.  **Mise en attente et Planification de tâches** : Si une action est une classe, on peut la stocker dans une file d'attente (comme *RabbitMQ* ou les tâches de fond *Laravel/Symfony*) pour qu'elle soit exécutée par le serveur à minuit ! Les objets peuvent s'envoyer ou se retarder facilement là où un simple appel de méthode disparaît vite.

## À savoir
*   **Surplus de classes/petits fichiers** : C'est le principal défaut (souvent le cas avec les gros design patterns). Si tu as 50 actions métier différentes, tu devras créer 50 classes `Commande...`. Le projet se remplit de centaines de mini-fichiers, mais on gagne une flexibilité absolue en retour.
