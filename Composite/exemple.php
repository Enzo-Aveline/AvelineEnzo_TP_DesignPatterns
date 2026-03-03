<?php

// ==========================================
// 1. Le Composant de base (Interface Commune)
// ==========================================
// Le contrat : Tout le monde (Feuille ou Composite) peut renvoyer un prix.

interface ElementPanier {
    public function getPrix(): float;
}


// ==========================================
// 2. La Feuille (Leaf)
// ==========================================
// C'est un élément simple (un objet final)
// Elle n'a pas d'enfants. Elle fait le vrai travail (renvoyer son propre prix).

class Produit implements ElementPanier {
    private string $nom;
    private float $prix;

    public function __construct(string $nom, float $prix) {
        $this->nom = $nom;
        $this->prix = $prix;
    }

    public function getPrix(): float {
        // Le produit se contente de retourner son prix unitaire
        return $this->prix;
    }

    public function getNom(): string {
        return $this->nom;
    }
}


// ==========================================
// 3. Le Composite (L'élément complexe)
// ==========================================
// C'est un élément qui contient "d'autres" éléments (qui peuvent être des feuilles ou d'autres composites).

class Boite implements ElementPanier {
    // Un tableau qui contient des ElementPanier (soit d'autres Boîtes, soit des Produits)
    private array $elements = [];

    // Méthode pour ajouter des enfants à la boîte
    public function ajouterElement(ElementPanier $element): void {
        $this->elements[] = $element;
    }

    public function getPrix(): float {
        $prixTotal = 0;
        
        // La Boîte délègue le travail ! Elle demande le prix à tous ses enfants.
        // C'est la magie de l'arbre et de la récursivité.
        foreach ($this->elements as $element) {
            $prixTotal += $element->getPrix();
        }
        
        // On rajoute 1€ de frais fixe "pour le carton" par boîte, pour l'exemple !
        return $prixTotal + 1.0; 
    }
}

// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

// 1. On crée des produits (Feuilles)
$telephone = new Produit("iPhone", 900.0);
$chargeur = new Produit("Chargeur Rapide", 25.0);
$ecouteurs = new Produit("AirPods", 150.0);
$carteCadeau = new Produit("Carte Cadeau", 50.0);

// 2. On crée "L'arbre" (Composites)

// Une petite boîte pour emballer le téléphone et son chargeur
$boiteSmartphone = new Boite();
$boiteSmartphone->ajouterElement($telephone);
$boiteSmartphone->ajouterElement($chargeur);

// Une autre petite boîte pour les accessoires
$boiteAccessoires = new Boite();
$boiteAccessoires->ajouterElement($ecouteurs);

// 3. Le Grand Carton (le sommet de l'arbre)
$grandCartonDeLivraison = new Boite();
// Le grand carton contient d'autres boîtes...
$grandCartonDeLivraison->ajouterElement($boiteSmartphone);
$grandCartonDeLivraison->ajouterElement($boiteAccessoires);
// ...et aussi des produits en vrac
$grandCartonDeLivraison->ajouterElement($carteCadeau);

// L'application s'en fout de savoir s'il y a 2 boîtes et 1 produit au 1er niveau.
// Elle demande VITE le prix global par un seul appel :
echo "Calcul du panier total avec le système de boîte...\n";
echo "Le prix global de la commande est de : " . $grandCartonDeLivraison->getPrix() . " €\n";
