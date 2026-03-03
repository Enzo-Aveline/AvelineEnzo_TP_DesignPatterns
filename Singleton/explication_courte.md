# Singleton - Résumé Rapide

**But**
Garantir qu'une classe n'aie **qu'une seule et unique instance** en mémoire pour toute l'application, et fournir un point d'accès global direct à celle-ci.

**Problème résolu**
- Gaspillage sévère si la création du composant est lourde et répétitive.
- Incohérences si 2 modules diffèrent sur l'état général de certaines règles (ex: deux configurations actives pour une seule application ou deux connexions simultanées à une base de données).

**La Solution**
La classe se verrouille elle-même : on coupe son constructeur au monde extérieur, et on force tout le monde à appeler une méthode gérée par la classe elle-même (`getInstance()`) qui crée l'objet la première fois, ou renvoie simplement l'existant s'il y en a eu une.

---

### La Structure (Classe Unique)

1. **Constructeur privé** (`private function __construct()`) : *Rend l'utilisation extérieure du `new Singleton()` proscrite et lance une erreur PHP.*
2. **Propriété statique privée** (`private static $instance`) : *Stocke en secret la seule et unique version de la classe.*
3. **Méthode statique publique** (`public static function getInstance()`) : *Point de contrôle : `if (self::$instance == null) { self::$instance = new self(); } return self::$instance;`*

---

### Avantages
- **Unicité prouvée**.
- **Accès global ultra-rapide** : Pas besoin de colporter l'objet en argument dans les 400 fonctions de toute l'application. On l'appelle simplement par `MonSingleton::getInstance()`.
- **Initialisation Paresseuse** : L'objet mémoire n'est créé que le jour où quelqu'un appelle le getInstance. S'il n'est jamais utilisé, il n'est jamais alloué.

### Inconvénient / Attention
- **"Anti-Pattern" en approche objet pure** : Il se déguise en variable globale. Souvent remplacé de nos jours par un mécanisme d'Injection de Dépendance (DI) natif dans les Frameworks (comme le Container dans Symfony ou Laravel).
- **Très difficile à tester (Moquer)** : L'état vit éternellement pendant l'exécution d'une batterie de tests, faussant les résultats suivants si on n'y prend pas garde.
