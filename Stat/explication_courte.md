# State (État) - Résumé Rapide

**But**
Permettre à un objet (machine, entité) de modifier totalement son comportement à la volée lorsque son état interne de fonctionnement change.

**Problème résolu**
- Un objet complexe (comme un Lecteur MP3 à boutons, un jeu vidéo, une Borne Navigo) pris au piège dans une méthode truffée de `if ($this->modeCourant == 'en_pause') { ... } elseif ($modeCourant == 'arrete') { ... }` tentant de deviner s'il peut ouvrir sa porte ou démarrer.
- La difficulté inouïe d'ajouter un énième statut dans l'application si on doit modifier les "if" partagés dans les 30 méthodes du programme existant.

**La Solution**
On prend chaque "Statut" existant, et on en fait une **Classe Dédiée** (`EtatEnPause`, `EtatEnMarche`). L'objet machine ne se cogne plus le nez sur des `ifs`. Quand on appuie sur la façade, la machine transmet ce courant électrique au statut enclenché à l'intérieur pour qu'il s'en occupe.

---

### Les 3 Éléments Clés (Structure)

1. **L'Interface / Classe Abstraite État** (`EtatLecteur`) : *Oblige chaque statut possible de la machine à prévoir une réponse à chaque bouton qui existe sur le boitier externe (ex: `boutonLecture()`, `boutonPause()`).*
2. **Les États Concrets** (`EtatArrete`, `EtatEnLecture`...) : *Chaque classe répond aux clics selon la manière la plus logique dans ce moment précis. Surtout, elle est autorisée à déloger l'état actuel et à se relayer au SUIVANT ! (Moi, 'EtatArrete', on m'a dit de play la musique :  je branche `new EtatEnLecture()` à l'intérieur de la machine).*
3. **Le Contexte** (`LecteurAudio`) : *La grosse machine principale, la façade que manipule le code appelant. Elle contient une propriété abritant le mode, à qui elle délègue aveuglément tout : `$this->etatActuel->boutonLecture($this);`.*

---

### Avantages
- **Mort au code spaghetti géant** : Un tiroir (une classe hermétique) par mode "EnPause", "Arrete", ou "Lancement", avec quelles sont les actions valides ou non pour ce mode.
- **Ajout Magique (Open/Closed)** : Créer une étape `EnVeilleProfonde` dans le MP3 exige la création d'un unique fichier, et on ne touche plus jamais au code "dur" de l'appareil.
- **Workflow / Transitions** : Le cheminement de la machine est pilotable étape par étape.

### Inconvénient / Attention
- **Différence avec la Stratégie** : Les schémas de classes sont les mêmes, mais la philosophie est opposée. En `Strategy`, le format est dirigé extérieurement ("Utiliser l'Algo PDF VS Algo Excel"). En `State`, ce sont les états "eux-mêmes" qui se mutent de l'un à l'autre de manière inhérente et cachée. (Changement naturel de l'état d'un Composant vs Injection Manuelle d'un Outil).
