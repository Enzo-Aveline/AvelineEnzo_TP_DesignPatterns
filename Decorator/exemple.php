<?php

// 1. L'Interface (Boisson)
interface Boisson {
    public function getDescription(): string;
    public function cout(): float;
}

// 2. Le Composant Concret (La base : un Café Simple)
class CafeSimple implements Boisson {
    public function getDescription(): string {
        return "Café Simple";
    }

    public function cout(): float {
        return 2.00; // Prix de base
    }
}

// Autre base possible
class Deca implements Boisson {
    public function getDescription(): string {
        return "Déca";
    }

    public function cout(): float {
        return 2.50;
    }
}

// 3. Le Décorateur de base
abstract class BoissonDecorator implements Boisson {
    protected $boisson;

    public function __construct(Boisson $boisson) {
        $this->boisson = $boisson;
    }

    public function getDescription(): string {
        return $this->boisson->getDescription();
    }

    public function cout(): float {
        return $this->boisson->cout();
    }
}

// 4. Les Décorateurs Concrets (Les ingrédients)

class Lait extends BoissonDecorator {
    public function getDescription(): string {
        return $this->boisson->getDescription() . ", Lait";
    }

    public function cout(): float {
        return $this->boisson->cout() + 0.50; // Ajoute 0.50 au prix total
    }
}

class Caramel extends BoissonDecorator {
    public function getDescription(): string {
        return $this->boisson->getDescription() . ", Caramel";
    }

    public function cout(): float {
        return $this->boisson->cout() + 0.75; // Ajoute 0.75 au prix total
    }
}

class Chantilly extends BoissonDecorator {
    public function getDescription(): string {
        return $this->boisson->getDescription() . ", Chantilly";
    }

    public function cout(): float {
        return $this->boisson->cout() + 1.00; // Ajoute 1.00 au prix total
    }
}

// --- TEST ---

echo "--- Commande 1 : Un café simple ---\n";
$monCafe1 = new CafeSimple();
echo "Description : " . $monCafe1->getDescription() . "\n";
echo "Prix : " . $monCafe1->cout() . "€\n\n";

echo "--- Commande 2 : Un café au lait ---\n";
// On "enveloppe" le café avec du lait
$monCafe2 = new CafeSimple();
$cafeAuLait = new Lait($monCafe2);
echo "Description : " . $cafeAuLait->getDescription() . "\n";
echo "Prix : " . $cafeAuLait->cout() . "€\n\n";

echo "--- Commande 3 : Un Caramel Macchiato gourmand (Café + Lait + Caramel + Chantilly) ---\n";
// On empile les décorateurs les uns sur les autres
$monCafe3 = new CafeSimple();
$gourmand = new Lait($monCafe3);
$gourmand = new Caramel($gourmand);
$gourmand = new Chantilly($gourmand);

// Notez comment la description et le prix s'accumulent
echo "Description : " . $gourmand->getDescription() . "\n";
echo "Prix : " . $gourmand->cout() . "€\n";
