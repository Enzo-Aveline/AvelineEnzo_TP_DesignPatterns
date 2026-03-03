<?php

// ==========================================
// 0. Le Produit Complexe
// ==========================================
// C'est l'objet final que l'on veut construire. Ses caractéristiques
// peuvent beaucoup varier selon la configuration choisie.

class Ordinateur {
    private array $composants = [];

    public function ajouterComposant(string $composant): void {
        $this->composants[] = $composant;
    }

    public function afficherConfiguration(): void {
        echo "Configuration du PC : " . implode(", ", $this->composants) . "\n";
    }
}

// ==========================================
// 1. L'Interface (Le Builder / Builder abstrait)
// ==========================================
// Contrat que tous les builders doivent respecter pour assembler le PC.

interface BuilderOrdinateur {
    public function reinitialiser(): void;
    public function installerCPU(): void;
    public function installerRAM(): void;
    public function installerStockage(): void;
    public function installerGPU(): void;
    public function ajouterLedsRGB(): void;
    public function getOrdinateur(): Ordinateur;
}

// ==========================================
// 2. Les Builders Concrets
// ==========================================
// Ils définissent concrètement quelles pièces mettre dans la machine.

class BuilderPCGamer implements BuilderOrdinateur {
    private Ordinateur $pc;

    public function __construct() {
        $this->reinitialiser();
    }

    public function reinitialiser(): void {
        $this->pc = new Ordinateur();
    }

    public function installerCPU(): void {
        $this->pc->ajouterComposant("Processeur Intel i9 14900K");
    }

    public function installerRAM(): void {
        $this->pc->ajouterComposant("32 Go RAM DDR5");
    }

    public function installerStockage(): void {
        $this->pc->ajouterComposant("SSD NVMe 2 To");
    }

    public function installerGPU(): void {
        $this->pc->ajouterComposant("Carte Graphique RTX 4090");
    }

    public function ajouterLedsRGB(): void {
        $this->pc->ajouterComposant("Ventilateurs RGB avec synchronisation musicale");
    }

    public function getOrdinateur(): Ordinateur {
        $resultat = $this->pc;
        $this->reinitialiser(); // Prêt pour une nouvelle construction (remet à zéro)
        return $resultat;
    }
}

class BuilderPCBureau implements BuilderOrdinateur {
    private Ordinateur $pc;

    public function __construct() {
        $this->reinitialiser();
    }

    public function reinitialiser(): void {
        $this->pc = new Ordinateur();
    }

    public function installerCPU(): void {
        $this->pc->ajouterComposant("Processeur Intel i3");
    }

    public function installerRAM(): void {
        $this->pc->ajouterComposant("8 Go RAM DDR4");
    }

    public function installerStockage(): void {
        $this->pc->ajouterComposant("SSD SATA 256 Go");
    }

    public function installerGPU(): void {
        $this->pc->ajouterComposant("Puce graphique intégrée (sans GPU dédié)");
    }

    public function ajouterLedsRGB(): void {
        // Un PC de bureau n'a pas besoin de Leds RGB, on ne fait rien
    }

    public function getOrdinateur(): Ordinateur {
        $resultat = $this->pc;
        $this->reinitialiser(); // Prêt pour une nouvelle construction (remet à zéro)
        return $resultat;
    }
}

// ==========================================
// 3. Le Directeur
// ==========================================
// Il connaît les "recettes" d'assemblage dans le bon ordre.

class DirecteurAssemblage {
    private BuilderOrdinateur $builder;

    public function setBuilder(BuilderOrdinateur $builder): void {
        $this->builder = $builder;
    }

    // Recette 1 : Un PC complet de base (sans fioritures)
    public function construirePCBasique(): void {
        $this->builder->installerCPU();
        $this->builder->installerRAM();
        $this->builder->installerStockage();
        $this->builder->installerGPU();
    }

    // Recette 2 : Un PC complet toutes options (avec Leds)
    public function construirePCToutesOptions(): void {
        $this->builder->installerCPU();
        $this->builder->installerRAM();
        $this->builder->installerStockage();
        $this->builder->installerGPU();
        $this->builder->ajouterLedsRGB();
    }
}

// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

$directeur = new DirecteurAssemblage();

// 1. On va commander un PC Gamer Complet
echo "--- Commande d'un PC Gamer Complet ---\n";
$builderGamer = new BuilderPCGamer();
$directeur->setBuilder($builderGamer);

// Le directeur dicte la recette complète
$directeur->construirePCToutesOptions();

// Le client récupère le produit fini directement auprès du builder
$monPCGamer = $builderGamer->getOrdinateur();
$monPCGamer->afficherConfiguration();


// 2. On change d'avis, on veut fabriquer un PC de Bureau simple pour la bureautique
echo "\n--- Commande d'un PC de Bureau Basique ---\n";
$builderBureau = new BuilderPCBureau();
$directeur->setBuilder($builderBureau);

// Le directeur utilise une autre recette, plus simple
$directeur->construirePCBasique();

// Et voilà un PC complètement différent, construit avec les mêmes méthodes
$monPCBureau = $builderBureau->getOrdinateur();
$monPCBureau->afficherConfiguration();


// 3. (Optionnel) Le client peut aussi se passer du directeur
// S'il veut une recette très spéciale qu'il a lui-même en tête
echo "\n--- Commande Sans Directeur (Le client assemble lui-même son PC 'Frankenstein') ---\n";
$builderCustom = new BuilderPCGamer();
$builderCustom->installerCPU();
$builderCustom->installerRAM();
$builderCustom->ajouterLedsRGB();
// Pas de carte graphique, pas de stockage... C'est pas fonctionnel, mais le Builder le permet !

$pcBizarre = $builderCustom->getOrdinateur();
$pcBizarre->afficherConfiguration();

