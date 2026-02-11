<?php

// Scénario : Une chaîne YouTube qui notifie ses abonnés lors de la sortie d'une nouvelle vidéo.
// C'est souvent plus parlant qu'un produit/prix.

// 1. Interface Sujet (L'objet observé)
interface Sujet {
    public function abonner(Observateur $obs);
    public function desabonner(Observateur $obs);
    public function notifier();
}

// 2. Interface Observateur (Celui qui écoute)
interface Observateur {
    public function actualiser(Sujet $message);
}

// 3. Sujet Concret : La Chaîne YouTube
class ChaineYoutube implements Sujet {
    private $nomChaine;
    private $derniereVideo;
    private $listeAbonnes = [];

    public function __construct($nom) {
        $this->nomChaine = $nom;
    }

    // Gestion des abonnés
    public function abonner(Observateur $obs) {
        $this->listeAbonnes[] = $obs;
    }

    public function desabonner(Observateur $obs) {
        $key = array_search($obs, $this->listeAbonnes, true);
        if ($key !== false) {
            unset($this->listeAbonnes[$key]);
        }
    }

    // Méthode pour prévenir tout le monde
    public function notifier() {
        echo "📢 Envoi des notifications à tous les abonnés...\n";
        foreach ($this->listeAbonnes as $abonne) {
            $abonne->actualiser($this);
        }
    }

    // L'action déclencheuse
    public function ajouterVideo($titre) {
        $this->derniereVideo = $titre;
        echo "\n📺 La chaîne '{$this->nomChaine}' vient de publier : \"$titre\"\n";
        $this->notifier();
    }

    public function getNom() { return $this->nomChaine; }
    public function getDerniereVideo() { return $this->derniereVideo; }
}

// 4. Observateur Concret : L'Utilisateur YouTube
class Utilisateur implements Observateur {
    private $pseudo;

    public function __construct($pseudo) {
        $this->pseudo = $pseudo;
    }

    // Réaction quand le sujet change
    public function actualiser(Sujet $chaine) {
        if ($chaine instanceof ChaineYoutube) {
            echo "   🔔 Notification pour {$this->pseudo} : Nouvelle vidéo \"{$chaine->getDerniereVideo()}\" sur {$chaine->getNom()} !\n";
        }
    }
}

// --- TEST ---

// 1. Création de la chaîne (Le Sujet)
$joueurDuGrenier = new ChaineYoutube("Joueur Du Grenier");

// 2. Création des utilisateurs (Les Observateurs)
$fan1 = new Utilisateur("Gamer42");
$fan2 = new Utilisateur("RetroFan");

// 3. Ils s'abonnent
$joueurDuGrenier->abonner($fan1);
$joueurDuGrenier->abonner($fan2);

// 4. Sortie d'une vidéo -> Tout le monde est notifié
$joueurDuGrenier->ajouterVideo("TEST : LES JEUX DISNEY");

// 5. Un utilisateur se désabonne
$joueurDuGrenier->desabonner($fan1);

// 6. Sortie d'une autre vidéo -> Seul celui qui reste abonné reçoit la notif
$joueurDuGrenier->ajouterVideo("HORS-SÉRIE : LES DESSINS ANIMÉS");
