# Facade (Façade)

## Problème de Conception
Imagine que tu doives interagir avec une librairie externe touffue ou un ensemble complexe de classes métier. Par exemple, pour faire afficher ne serait-ce qu'une page, tu dois instancier 5 ou 6 classes différentes, les initialiser dans le bon ordre, te souvenir de leurs liaisons et appeler les bonnes méthodes.

Si tu écris ces instructions (la "plomberie") directement dans ton code client un peu partout, ton code devient **incroyablement couplé** au système sous-jacent et particulièrement illisible. 
De plus, le jour où l'auteur met à jour sa machine avec un format un peu différent, ton client est brisé dans toute son arborescence.

*L'exemple du quotidien* : Pour regarder un film de nos jours, c'est parfois l'usine à gaz... Il faut fermer les volets, allumer la télévision avec la télécommande de la box, s'assurer de mettre le bon canal HDMI, allumer l'amplificateur audio, régler son volume au bon cran, puis allumer le Lecteur DVD (ou l'Apple TV) pour enfin chercher la lecture du film.

## Solution : Mettre en place une Façade
Le pattern **Facade** fournit une interface simple, lisible et unifiée à cet amas de complexité.

Au lieu que le client interagisse avec des dizaines d'objets, la Façade est une classe qui agit comme un **gros bouton "Macro"**. Elle offre une "devanture" (d'où son nom) parfaite, avec des commandes compréhensibles qui vont déclencher le travail par derrière sur de multiples sous-systèmes en coulisse.

## Structure du code (Analyse de l'exemple du Home Cinéma)

### 1. Les Sous-Systèmes complexes (`Television`, `AmplificateurSon`, `LecteurDVD`, `Lumieres`)
Ce sont de nombreuses classes qui contiennent la "vraie" logique et l'état métier. Leurs méthodes sont spécifiques et nombreuses. Ces classes ne connaissent absolument pas l'existence de la Façade (le lien n'est que dans un seul sens : on regarde "à travers" la façade). Ces petites classes font leur vie indépendamment.

### 2. La Façade (`HomeCinemaFacade`)
C'est le panneau de contrôle générique qu'on livre au client qui ne veut pas avoir mal à la tête. Cette classe conserve secrètement une référence vers tous les objets nécessaires dans sa tuyauterie interne. 
```php
class HomeCinemaFacade {
    // ...
    public function regarderFilm(string $film): void {
        // La méthode orchestre des dizaines d'actions minutées en 1 seule ligne pour l'utilisateur final !
        $this->lumieres->tamiser();
        $this->tv->allumer();
        $this->ampli->allumer();
        $this->ampli->reglerVolume(7);
        $this->dvd->lire($film);
    }
}
```

### 3. Le Client (Ton application)
Le code client qui appelait tous les objets devient magiquement épuré en une ligne sans avoir à comprendre les dépendances techniques ou l'ordre d'allumage des machines :
```php
$homeCinema->regarderFilm("Inception");
```

## Avantages de cette approche

1.  **Simplicité extrême (Couplage Lâche)** : Le client est blindé face à la complexité interne d'une "usine à gaz". Il manipule la devanture propre de la vitrine sans errer dans la cave et l'entrepôt du magasin.
2.  **Point d'entrée de mise à niveau / résilience parfaite** : Si un jour tu supprimes le lecteur DVD pour utiliser une clé USB branchée derrière la télé... tu modifieras cette nouvelle façon de démarrer la machine *strictement à l'intérieur de ta Façade*. Aucun autre programmeur ne verra la diff, on continuera tous simplement de presser la touche unique `regarderFilm`.
3.  **Encapsulation sous architecture hiérarchique** : L'idéal absolu pour créer des "couches logicielles". Les entités de persistance au fond, puis des dépôts, des services... puis une façade Controller. Chaque couche ne présente au client de devant que la devanture finalisée.

## À savoir
*   **Ne masque pas tout s'il ne le faut pas** : La Façade est là en complément pour "faciliter" le comportement la majorité du temps (les chemins heureux dits "Happy Path"). Cela n'empêche jamais un autre développeur "expert" du code client de contourner la façade pour aller manipuler la classe *AmpliSon* soi-même pour régler l'équaliseur sur du classique si c'est vraiment requis.
*   **Danger du *God Object* (L'objet Dieu)** : Contrairement à d'autres DP, l'ennemi juré ici, c'est que votre classe Façade grossisse tellement en offrant toutes les combinaisons possibles (en pointant sur 30 sous-systèmes liés) qu'elle devienne monstrueuse. Pensez dans ce cas-ci à rajouter et échelonner des Façades "sectorielles" (1 facade `Video` et 1 facade `Audio` à l'intérieur de la globale).
