<?php

// ==========================================
// 1. L'Interface Cible (L'existant : La prise Française)
// ==========================================
// Dans notre maison, toutes les prises sont aux normes françaises (220V).
// Notre système s'attend à ce que tout appareil qu'on branche implémente cette interface.

interface PriseFrancaise {
    public function brancherSur220V(): void;
}

// Un appareil français classique (fonctionne nativement)
class CafetiereFrancaise implements PriseFrancaise {
    public function brancherSur220V(): void {
        echo "Cafetière Française : Branchée sur 220V. Le café coule...\n";
    }
}


// ==========================================
// 2. Le Service Externe ("L'Adaptee" : L'appareil Américain)
// ==========================================
// C'est un appareil acheté à l'étranger (ou une librairie externe fournie par un tiers).
// Il n'est PAS compatible avec `PriseFrancaise`. Il a ses propres méthodes (110V).

class ChargeurTelephoneAmericain {
    public function brancherSur110V(): void {
        echo "Chargeur US : Branché sur 110V. Le téléphone charge...\n";
    }
}


// ==========================================
// 3. L'Adaptateur (Le traducteur : L'adaptateur de voyage)
// ==========================================
// Impossible de brancher l'appareil américain directement dans le mur.
// On crée un adaptateur qui "fait semblant" d'être une prise française, 
// mais qui à l'intérieur, fait le lien avec l'appareil américain.

class AdaptateurPriseAmericaine implements PriseFrancaise {
    private ChargeurTelephoneAmericain $appareilUS;

    // L'adaptateur "enveloppe" l'appareil incompatible
    public function __construct(ChargeurTelephoneAmericain $appareilUS) {
        $this->appareilUS = $appareilUS;
    }

    // L'adaptateur obéit à l'interface française attendue par le mur...
    public function brancherSur220V(): void {
        echo "Adaptateur : Je reçois du 220V, je le transforme en 110V...\n";
        
        // ... et convertit l'appel pour l'appareil américain caché à l'intérieur !
        $this->appareilUS->brancherSur110V();
    }
}


// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

echo "### Arrivée dans la maison (Prises 220V) ###\n\n";

// 1. Appareil natif : OK
$cafetiere = new CafetiereFrancaise();
$cafetiere->brancherSur220V();

echo "\n";

// 2. L'appareil étranger seul : ÉCHEC 
// (Impossible de faire $chargeurUS->brancherSur220V(), la méthode n'existe pas !)
$chargeurUS = new ChargeurTelephoneAmericain();

// 3. Utilisation de l'adaptateur : OK
// On branche le chargeur US dans l'adaptateur...
$adaptateurDeVoyage = new AdaptateurPriseAmericaine($chargeurUS);

// ... et on branche l'adaptateur dans le mur français !
$adaptateurDeVoyage->brancherSur220V();

