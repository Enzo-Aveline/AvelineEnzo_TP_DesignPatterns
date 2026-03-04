<?php

// ==========================================
// 1. Les Sous-Systèmes Complexes
// ==========================================
// Il y a plein de classes différentes qui font chacune une partie du travail.
// Le client ne veut pas avoir à toutes les instancier et les manipuler une par une à chaque fois.

class Television {
    public function allumer(): void { echo "Télévision : Allumée\n"; }
    public function eteindre(): void { echo "Télévision : Éteinte\n"; }
}

class AmplificateurSon {
    public function allumer(): void { echo "Ampli : Allumé\n"; }
    public function reglerVolume(int $niveau): void { echo "Ampli : Volume réglé sur $niveau\n"; }
    public function eteindre(): void { echo "Ampli : Éteint\n"; }
}

class LecteurDVD {
    public function allumer(): void { echo "Lecteur DVD : Allumé\n"; }
    public function lire(string $film): void { echo "Lecteur DVD : Lecture du film '$film' en cours...\n"; }
    public function eteindre(): void { echo "Lecteur DVD : Éteint\n"; }
}

class Lumieres {
    public function tamiser(): void { echo "Lumières : Tamisées pour le film\n"; }
    public function allumer(): void { echo "Lumières : Allumées (fin du film)\n"; }
}

// ==========================================
// 2. La Façade (Facade)
// ==========================================
// C'est une classe de "Devanture" qui a accès à tous les sous-systèmes complexes de "l'arrière boutique".
// Elle va fournir des méthodes faciles à utiliser (des macros) au client.

class HomeCinemaFacade {
    private Television $tv;
    private AmplificateurSon $ampli;
    private LecteurDVD $dvd;
    private Lumieres $lumieres;

    public function __construct(
        Television $tv, 
        AmplificateurSon $ampli, 
        LecteurDVD $dvd, 
        Lumieres $lumieres
    ) {
        $this->tv = $tv;
        $this->ampli = $ampli;
        $this->dvd = $dvd;
        $this->lumieres = $lumieres;
    }

    // Le gros bouton rouge "CINÉMA !" pour le client.
    public function regarderFilm(string $film): void {
        echo "\n--- Préparation de la séance cinéma ---\n";
        // La méthode orchestre elle-même l'ordre exact et la plomberie des différents sous-systèmes !
        $this->lumieres->tamiser();
        $this->tv->allumer();
        $this->ampli->allumer();
        $this->ampli->reglerVolume(7);
        $this->dvd->allumer();
        $this->dvd->lire($film);
    }

    // Le bouton "FIN DE SÉANCE"
    public function arreterFilm(): void {
        echo "\n--- Fin de la séance, on éteint tout ---\n";
        $this->dvd->eteindre();
        $this->ampli->eteindre();
        $this->tv->eteindre();
        $this->lumieres->allumer();
    }
}

// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

// 1. Instanciation de tous les sous-systèmes complexes (souvent fait par l'Application via Docker/Services)
$tv = new Television();
$ampli = new AmplificateurSon();
$dvd = new LecteurDVD();
$lumieres = new Lumieres();

// 2. On instancie la Façade en lui passant l'outillage
$homeCinema = new HomeCinemaFacade($tv, $ampli, $dvd, $lumieres);

// 3. Le client a maintenant une vie SUPER FACILE. 
// Plus besoin de manipuler et de connaître 4 objets, 4 méthodes distinctes et dans un ordre précis pour se lancer un film !
$homeCinema->regarderFilm("Inception");

sleep(1); // On simule le temps du film...

$homeCinema->arreterFilm();

