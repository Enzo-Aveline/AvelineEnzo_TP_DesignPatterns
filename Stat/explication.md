# State (État)

## Problème de Conception
Imagine que tu développes l'interface utilisateur d'un **Lecteur Audio** (un baladeur MP3, Spotify...). Ton lecteur possède de manière évidente 3 états de fonctionnement : `Arrêté`, `En Lecture`, et `En Pause`.

Si tu codes le comportement des boutons avec des gros blocs de `if` ou des `switch`, les méthodes de la machine vont très vite devenir un cauchemar :
```php
class LecteurAudio {
    private string $statut = 'arrete';

    public function appuyerLecture() {
        if ($this->statut == 'arrete') {
            echo "Lancement de la musique";
            $this->statut = 'en_lecture';
        } elseif ($this->statut == 'en_lecture') {
            echo "Arrêt de la musique";
            $this->statut = 'arrete'; // Appuyer sur play en lecture nous l'arrête complètement
        } elseif ($this->statut == 'en_pause') {
            echo "Reprise de la musique";
            $this->statut = 'en_lecture';
        }
    }
    // Idem pour le bouton Pause avec encore des if... et le bouton Suivant...
}
```
**Problèmes :**
1. **Les "Switch" Géants** : Plus tu as de boutons cliquables et de modes d'utilisation (En Chargement, Sans Rèseau, Playlist Finie...), plus tes méthodes vont ressembler à des plats de spaghettis remplis de conditions intraitables.
2. **Maintenance Infernale** : Si on ajoute l'état `Bloque` (pour ne pas appuyer dans la poche sans faire exprès), il faudra aller modifier les "if" à l'intérieur de *toutes* les méthodes du Lecteur.

## Solution : Les États deviennent des Objets
Le pattern **State** propose d'extraire la logique de "ce qui doit se passer à cet instant précis"... dans **une classe dédiée par Statut**.

Ce n'est plus le `LecteurAudio` qui se prend la tête avec ses propres états et ses "if". Ton MP3 possède simplement une propriété qui stocke pour le moment un **objet** de type `EtatLecteur` (par exemple `new EtatEnLecture()`). 
Quand tu appuies sur le bouton "Lecture", le MP3 ne réfléchit pas ; il dit juste à l'état branché dedans : *"Hé l'état, quelqu'un a cliqué sur 'Lecture' sur ma poignée, c'est à toi de gérer la réaction !"*.

## Structure du code (Analyse de l'exemple du lecteur MP3)

### 1. L'Interface d'État (`EtatLecteur`)
Le contrat pour tous les états de notre machine musicale. Elle liste impérativement tous les boutons ("boutonLecture", "boutonPause") sur lesquels l'utilisateur peut appuyer, obligeant 100% des statuts à répondre à ce clic éventuel.
```php
interface EtatLecteur {
    public function boutonLecture(LecteurAudio $lecteur): void;
    // ...
}
```

### 2. Les États Concrets (`EtatArrete`, `EtatEnLecture`...)
C'est le génie du pattern : **chaque état sait comment il doit réagir aux clics, et il commande le changement vers le prochain état de l'objet global.**
```php
class EtatEnPause implements EtatLecteur {
    public function boutonLecture(LecteurAudio $lecteur): void {
        echo "Reprise de la musique !";
        // C'est l'État lui-même qui donne l'ordre à la machine de changer de mode !
        $lecteur->setEtat(new EtatEnLecture()); 
    }
}
```

### 3. Le Contexte (`LecteurAudio`)
La "grosse boîte", le MP3, que tu as dans la main en tant qu'utilisateur. Son utilité interne à la seconde dépend exclusivement de l'objet "État/Cassette" dans son ventre.
```php
class LecteurAudio {
    private EtatLecteur $etat;

    public function appuyerLecture() {
        // Le lecteur délègue la pression du bouton à l'état qui est dans son ventre
        $this->etat->boutonLecture($this);
    }
}
```

## Avantages de cette approche

1.  **Mort aux `Switch/if`** : Le code devient incroyablement clair et hermétique. Tu ouvres la classe `EtatEnPause`, et tu sais en un coup d'œil exactement comment va réagir la machine à tous les boutons du cadran si elle est dans ce mode précis, sans polluer la vue avec les autres modes.
2.  **Facilité d'ajout (Open/Closed)** : Si tu dois créer le mode `ModeVeille`, tu crées simplement une nouvelle classe de toute pièce, et tu n'as absolument rien à recâbler à l'intérieur des autres classes ou du MP3 !
3.  **Machine à états (Transitions claires)** : L'enchaînement des étapes de fonctionnement saute aux yeux, et il est orchestré directement par les classes d'états qui savent dans quel état elles s'apprêtent à plonger la machine (Les transitions).

## À savoir
*   **Différence cruciale avec STRATEGY** : Le code du pattern State ressemble *exactement* à 100% au diagramme du pattern **Strategy** ! Mais leur *philosophie* est opposée.
    - Dans Strategy : L'algorithme se fiche des autres algorithmes. Le calcul reste dans l'objet. C'est l'utilisateur devant l'écran qui le configure manuellement pour un calcul mathématique : (`$gps->setStrategie(new Velo)`). Une stratégie ne déclenche jamais d'autre stratégie.
    - Dans State : **Les états sont comme un automate connecté, ils se passent le relais !** Ils changent "eux-mêmes" l'état profond du Lecteur sans intervention du code appelant (ex : `EtatEnPause` branche et démarre un `new EtatEnLecture()` à l'intérieur du MP3 et s'évanouit).
