<?php

class TourDeControle
{
    private static ?TourDeControle $instance = null;
    private array $pistes;

    // Constructeur privé on créer les pistes
    private function __construct()
    {
        echo "🏗️  [Singleton] Construction de la Tour de Contrôle principale.\n";
        $this->pistes = [
            'Piste 1' => 'LIBRE',
            'Piste 2' => 'LIBRE',
            'Piste 3' => 'OCCUPÉE (Maintenance)'
        ];
    }

    // Empêche le clonage de l'instance
    private function __clone() {}

    // Retourne l'instance unique
    public static function getInstance(): TourDeControle
    {
        if (self::$instance === null) {
            self::$instance = new TourDeControle();
        }
        return self::$instance;
    }

    // Méthode métier : Demande d'atterrissage
    public function demanderAtterrissage(string $codeAvion): void
    {
        echo "📞 Avion $codeAvion : Demande d'atterrissage...\n";

        // On cherche une piste libre
        foreach ($this->pistes as $nomPiste => $statut) {
            if ($statut === 'LIBRE') {
                $this->pistes[$nomPiste] = "OCCUPÉE par $codeAvion";
                echo "   ✅ Tour : Autorisation accordée sur $nomPiste.\n";
                return;
            }
        }

        echo "   ❌ Tour : Négatif $codeAvion, toutes les pistes sont pleines. Veuillez patienter.\n";
    }

    // Méthode métier : Libérer une piste
    public function libererPiste(string $codeAvion, string $nomPiste): void
    {
        if (isset($this->pistes[$nomPiste]) && strpos($this->pistes[$nomPiste], $codeAvion) !== false) {
            $this->pistes[$nomPiste] = 'LIBRE';
            echo "ℹ️  L'avion $codeAvion a libéré la $nomPiste.\n";
        } else {
            echo "⚠️  Erreur : L'avion $codeAvion n'est pas sur la $nomPiste.\n";
        }
    }

    // Affiche l'état actuel des pistes
    public function afficherEtat(): void
    {
        echo "📊 État des Pistes : " . json_encode($this->pistes) . "\n";
    }
}

// ==========================================
// SCÉNARIO DE TEST
// ==========================================

echo "=== ✈️  SIMULATION AÉROPORT (SINGLETON) ✈️  ===\n\n";

// 1. Premier appel : L'instance est créée
$tour1 = TourDeControle::getInstance();
$tour1->demanderAtterrissage("AF101");
$tour1->afficherEtat();

echo "\n----------------------------------\n";

// 2. Second appel : On récupère la MÊME instance
$tour2 = TourDeControle::getInstance();
$tour2->demanderAtterrissage("BA202");
$tour2->afficherEtat();

echo "\n----------------------------------\n";

// 3. Troisième appel : Toujours la même instance, état partagé
$tour3 = TourDeControle::getInstance();
$tour3->demanderAtterrissage("LH303"); // Devrait être refusé car Piste 1 & 2 occupées, 3 en maintenance

// Vérification que c'est bien le même objet
if ($tour1 === $tour3) {
    echo "\n✅ TEST RÉUSSI : \$tour1 et \$tour3 sont bien la même instance !\n";
}
