<?php

// ==========================================
// 1. L'Interface de l'État (State)
// ==========================================
// C'est le contrat pour l'état de notre Lecteur Audio.
// Quelles sont les interactions possibles de l'utilisateur avec la machine ?

interface EtatLecteur {
    public function boutonLecture(LecteurAudio $lecteur): void;
    public function boutonPause(LecteurAudio $lecteur): void;
}

// ==========================================
// 2. Les États Concrets (Concrete States)
// ==========================================
// Chaque classe représente un état précis du lecteur (Arrêté, En Lecture, En Pause).
// Elles définissent comment les boutons réagissent dans l'état actuel et changent l'état du lecteur.

class EtatArrete implements EtatLecteur {
    public function boutonLecture(LecteurAudio $lecteur): void {
        echo "[Arrêté] Lancement de la musique ! 🎵\n";
        // On change l'état du lecteur vers "En Lecture"
        $lecteur->setEtat(new EtatEnLecture());
    }

    public function boutonPause(LecteurAudio $lecteur): void {
        echo "[Arrêté] Impossible de mettre en pause, la musique n'est pas lancée.\n";
    }
}

class EtatEnLecture implements EtatLecteur {
    public function boutonLecture(LecteurAudio $lecteur): void {
        echo "[En Lecture] La musique joue déjà, je l'arrête complètement. 🛑\n";
        // Appuyer sur "Play" quand on lit, on décide que ça l'arrête (Stop)
        $lecteur->setEtat(new EtatArrete());
    }

    public function boutonPause(LecteurAudio $lecteur): void {
        echo "[En Lecture] Mise en pause de la musique. ⏸️\n";
        // On change l'état vers "En Pause"
        $lecteur->setEtat(new EtatEnPause());
    }
}

class EtatEnPause implements EtatLecteur {
    public function boutonLecture(LecteurAudio $lecteur): void {
        echo "[En Pause] Reprise de la musique ! ▶️\n";
        // On change l'état vers "En Lecture"
        $lecteur->setEtat(new EtatEnLecture());
    }

    public function boutonPause(LecteurAudio $lecteur): void {
        echo "[En Pause] La musique est déjà en pause.\n";
    }
}

// ==========================================
// 3. Le Contexte (Context)
// ==========================================
// La véritable machine, le lecteur MP3. Il ne gère aucun "if($etat == 'en_lecture')".
// Il délègue toute l'intelligence aux objets États.

class LecteurAudio {
    private EtatLecteur $etatActuel;

    public function __construct() {
        // Au démarrage, le lecteur est toujours à l'arrêt.
        $this->etatActuel = new EtatArrete();
    }

    public function setEtat(EtatLecteur $nouvelEtat): void {
        $this->etatActuel = $nouvelEtat;
    }

    // Le lecteur n'a aucune logique interne pour ses boutons !
    // Il donne l'information à son état actuel. C'est l'État qui gère le clic !
    public function appuyerLecture(): void {
        $this->etatActuel->boutonLecture($this);
    }

    public function appuyerPause(): void {
        $this->etatActuel->boutonPause($this);
    }
}

// ==========================================
// --- TEST DU CODE CLIENT ---
// ==========================================

echo "### Démarrage du baladeur MP3 ###\n\n";

$mp3 = new LecteurAudio(); // Commence par défaut en "Arrete"

// J'appuie sur le bouton Play alors qu'il est arrêté
$mp3->appuyerLecture(); // Lance la musique -> (Passe EnLecture)

// J'appuie sur le bouton Play alors qu'il lit 
$mp3->appuyerLecture(); // Arrête la musique -> (Passe Arrete)

// Oups, la musique était stoppée, j'appuie sur Pause
$mp3->appuyerPause(); // Ne fait rien, impossible

// Je relance la musique
$mp3->appuyerLecture(); // Lance la musique -> (Passe EnLecture)

// Quelqu'un me parle, je mets en pause
$mp3->appuyerPause(); // Met en pause -> (Passe EnPause)

// La personne me parle encore, je rappuie sur pause sans faire exprès
$mp3->appuyerPause(); // Ne fait rien, déjà en pause

// Je reprends ma musique 
$mp3->appuyerLecture(); // Reprise -> (Passe EnLecture)

