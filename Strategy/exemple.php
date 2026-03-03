<?php

// ==========================================
// 1. L'Interface de la Stratégie
// ==========================================
// Contrat que toutes les stratégies (les algorithmes) doivent respecter.
interface StrategieItineraire {
    public function calculerItineraire(string $depart, string $arrivee): string;
}

// ==========================================
// 2. Les Stratégies Concrètes (Les différents algorithmes)
// ==========================================

class ItineraireVoiture implements StrategieItineraire {
    public function calculerItineraire(string $depart, string $arrivee): string {
        return "Trajet en VOITURE de $depart à $arrivee : Prendre l'autoroute A6 (Durée : 45 min).";
    }
}

class ItineraireVelo implements StrategieItineraire {
    public function calculerItineraire(string $depart, string $arrivee): string {
        return "Trajet à VÉLO de $depart à $arrivee : Prendre la piste cyclable le long du canal (Durée : 2h).";
    }
}

class ItineraireTransportEnCommun implements StrategieItineraire {
    public function calculerItineraire(string $depart, string $arrivee): string {
        return "Trajet en BUS de $depart à $arrivee : Prendre la ligne 4 puis le tramway B (Durée : 1h15).";
    }
}

// ==========================================
// 3. Le Contexte (L'objet de l'application qui va déléguer le travail)
// ==========================================
// C'est notre GPS. Il ne sait PAS comment calculer la route lui-même.
// Il utilise la stratégie qu'on lui a donnée.

class NavigateurGPS {
    // Le GPS garde une stratégie en mémoire
    private StrategieItineraire $strategie;

    // On peut définir la stratégie dès la création du GPS...
    public function __construct(StrategieItineraire $strategie) {
        $this->strategie = $strategie;
    }

    // ... et surtout, on peut CHANGER la stratégie dynamiquement !
    public function setStrategie(StrategieItineraire $strategie): void {
        $this->strategie = $strategie;
    }

    // L'action principale du GPS
    public function lancerGuidage(string $depart, string $arrivee): void {
        echo "Calcul en cours...\n";
        // Le contexte délègue le travail à la stratégie actuelle
        $resultat = $this->strategie->calculerItineraire($depart, $arrivee);
        echo $resultat . "\n";
    }
}

// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

$depart = "Paris";
$arrivee = "Versailles";

// 1. On configure le GPS avec une stratégie par défaut (la voiture)
$gps = new NavigateurGPS(new ItineraireVoiture());

echo "--- Mode Voiture ---\n";
$gps->lancerGuidage($depart, $arrivee);

// 2. Bouchon sur l'autoroute ! On change la stratégie DYNAMIQUEMENT.
echo "\n--- Oh non, des bouchons ! Passage en mode Vélo ---\n";
$gps->setStrategie(new ItineraireVelo());
$gps->lancerGuidage($depart, $arrivee);

// 3. Il pleut, on prend le bus.
echo "\n--- Il commence à pleuvoir. Passage en mode Transports ---\n";
$gps->setStrategie(new ItineraireTransportEnCommun());
$gps->lancerGuidage($depart, $arrivee);
