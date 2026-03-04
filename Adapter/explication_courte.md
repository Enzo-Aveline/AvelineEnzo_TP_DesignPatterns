# Adapter (Adaptateur) - Résumé Rapide

**But**
Permettre à des objets / systèmes ayant des interfaces incompatibles de travailler ensemble sans modifier leur code externe. 

**Problème résolu**
- Interagir avec une API externe, une librairie tierce ou un composant qui s'attend à recevoir des appels/données très différentes de comment notre système a l'habitude de dialoguer.
- Refus de polluer le code natif de l'application seulement pour s'adapter à une technologie tierce.
- (La métaphore : Vouloir brancher un chargeur américain en 110V sur une prise française en 220V).

**La Solution**
Créer une classe intermédiaire "Traductrice" : **l'Adaptateur**. Le client/la maison branche cet objet intermédiaire dans le mur (en appelant sa méthode native). Secrètement à l'intérieur, l'Adaptateur va "convertir le voltage" (la donnée) et appeler concrètement le service externe associé de l'autre côté.

---

### Les 3 Éléments Clés (Structure)

1. **L'Interface Cible / Murale** (`PriseFrancaise`) : *L'interface de notre système natif. L'endroit familier où le client s'attend à se brancher.*
2. **Le Composant / Service Externe** (`ChargeurAmericain`) : *Le code qu'on veut brancher, mais dont l'interface est techniquement incompatible (et qu'on ne veut/peut pas modifier).*
3. **L'Adaptateur** (`AdaptateurVoyage`) : *La classe-traductrice qui implémente l'Interface Cible (elle prend l'apparence de notre système), mais qui englobe (wrapp) en secret le composant étranger et convertit pour lui les appels entrants et sortants.*

---

### Avantages
- **Couplage Lâche/Résilience (SRP)** : Toute "bidouille / traduction / conversion complexe" (voltage, format JSON/Array) est enfouie dans l'adaptateur. Conséquence : la plomberie reste extrêmement propre du côté de l'application cliente.
- **Ouverture sans Casse (OCP)** : On créé 1000 nouveaux adaptateurs pour diverses bibliothèques étrangères sans la moindre rature dans les contrats de l'application coeur. (Très propice à 'l'Architecture Hexagonale').

### Inconvénient / Attention
- **Complexité Structurelle** : Ça multiplie très vite le nombre de fichiers, d'interfaces et de classes d'emballage (`wrappers`). 
- **Parfois pas nécessaire** : Si la classe prétendûment incompatible est en réalité un objet écrit par vous-même il y a 3 mois, c'est parfois meilleur et moins lourd de re-factoriser l'objet nativement plutôt que de rajouter un énième adaptateur "tampon" dessus.
