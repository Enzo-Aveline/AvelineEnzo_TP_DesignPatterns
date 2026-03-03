<?php

// ==========================================
// 1. Le Récepteur (Receiver) : L'objet qui fait le vrai travail
// ==========================================
// C'est l'objet "métier" à la fin de la chaîne.

class Lampe {
    public function allumer(): void {
        echo "💡 La lampe du salon est ALLUMÉE.\n";
    }

    public function eteindre(): void {
        echo "🌑 La lampe du salon est ÉTEINTE.\n";
    }
}

// ==========================================
// 2. L'Interface Commande (Command)
// ==========================================
// Le contrat : toute action doit pouvoir être exécutée (et annulée !)

interface Commande {
    public function executer(): void;
    public function annuler(): void; // Le grand pouvoir du Command : le Undo !
}


// ==========================================
// 3. Les Commandes Concrètes
// ==========================================
// Elles font le pont entre celui qui déclenche l'action (télécommande) et 
// celui qui réalise l'action finale (la lampe).

class CommandeAllumerLampe implements Commande {
    private Lampe $lampe;

    // La commande sait de quelle lampe elle s'occupe
    public function __construct(Lampe $lampe) {
        $this->lampe = $lampe;
    }

    public function executer(): void {
        $this->lampe->allumer();
    }

    public function annuler(): void {
        // L'inverse d'allumer, c'est éteindre.
        $this->lampe->eteindre(); 
    }
}

class CommandeEteindreLampe implements Commande {
    private Lampe $lampe;

    public function __construct(Lampe $lampe) {
        $this->lampe = $lampe;
    }

    public function executer(): void {
        $this->lampe->eteindre();
    }

    public function annuler(): void {
        $this->lampe->allumer();
    }
}

// ==========================================
// 4. L'Invocateur (Invoker) : Celui qui déclenche la commande
// ==========================================
// C'est notre objet "Interface Utilisateur" (bouton, télécommande, raccourci clavier...).
// Il n'a AUCUNE idée de ce qu'il contrôle (TV, Lampe, Porte...). 
// Il sait juste crier : "Exécute ça !"

class Telecommande {
    private Commande $commandeActuelle;
    private array $historique = []; // Pour mémoriser les actions et pouvoir faire Ctrl+Z

    // On assigne une action au bouton de la télécommande
    public function setCommande(Commande $commande): void {
        $this->commandeActuelle = $commande;
    }

    // On appuie sur le bouton
    public function appuyerBoutonAction(): void {
        if (isset($this->commandeActuelle)) {
            $this->commandeActuelle->executer();
            // On sauvegarde l'action dans l'historique !
            $this->historique[] = $this->commandeActuelle;
        }
    }

    // On appuie sur la touche Undo
    public function appuyerBoutonAnnuler(): void {
        if (!empty($this->historique)) {
            // On récupère la dernière commande exécutée pour l'inverser
            $derniereCommande = array_pop($this->historique);
            echo "↪️  [Touche Undo pressée] : ";
            $derniereCommande->annuler();
        } else {
            echo "Rien à annuler !\n";
        }
    }
}

// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

// 1. Création des récepteurs (la vraie lampe dans la maison)
$lumiereSalon = new Lampe();

// 2. Création des commandes liées à cet appareil
$allumerSalon = new CommandeAllumerLampe($lumiereSalon);
$eteindreSalon = new CommandeEteindreLampe($lumiereSalon);

// 3. Création de l'invocateur (la télécommande universelle)
$maTelecommande = new Telecommande();

echo "--- Utilisation normale ---\n";
// Je configure la télécommande avec le sort 'Allumer', puis j'appuie
$maTelecommande->setCommande($allumerSalon);
$maTelecommande->appuyerBoutonAction();

// Je configure avec le sort 'Éteindre', et j'appuie
$maTelecommande->setCommande($eteindreSalon);
$maTelecommande->appuyerBoutonAction();


echo "\n--- La magie du Undo (Annuler / Ctrl+Z) ---\n";
// "Zut, je me suis trompé, je veux revenir en arrière !"
$maTelecommande->appuyerBoutonAnnuler(); // Annule "Éteindre" -> Allume
$maTelecommande->appuyerBoutonAnnuler(); // Annule "Allumer" -> Éteint

