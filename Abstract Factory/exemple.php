<?php

// ===============================================
// 1. Les Produits (On garde ton Payment, et on ajoute un Reçu pour faire une "famille")
// ===============================================

interface Payment {
    public function processPayment($amount);
}
interface Receipt {
    public function printReceipt($amount);
}

// --- Famille CreditCard ---
class CreditCardPayment implements Payment {
    public function processPayment($amount) { echo "Processing credit card payment of $amount\n"; }
}
class CreditCardReceipt implements Receipt {
    public function printReceipt($amount) { echo "Printing credit card receipt for $amount\n"; }
}

// --- Famille PayPal ---
class PayPalPayment implements Payment {
    public function processPayment($amount) { echo "Processing PayPal payment of $amount\n"; }
}
class PayPalReceipt implements Receipt {
    public function printReceipt($amount) { echo "Printing PayPal receipt for $amount\n"; }
}


// ===============================================
// 2. L'Abstract Factory (Interface)
// L'usine sait créer TOUTE LA FAMILLE en même temps
// ===============================================
interface PaymentAbstractFactory {
    public function createPayment(): Payment;
    public function createReceipt(): Receipt;
}

// Usine concrète pour CreditCard
class CreditCardFactory implements PaymentAbstractFactory {
    public function createPayment(): Payment { return new CreditCardPayment(); }
    public function createReceipt(): Receipt { return new CreditCardReceipt(); }
}

// Usine concrète pour PayPal
class PayPalFactory implements PaymentAbstractFactory {
    public function createPayment(): Payment { return new PayPalPayment(); }
    public function createReceipt(): Receipt { return new PayPalReceipt(); }
}


// ===============================================
// 3. Comme dans ton exemple : Génération dynamique
// ===============================================
class FactoryProvider
{
    public static function getFactory($type): PaymentAbstractFactory
    {
        $classname = $type . "Factory";
        return new $classname();
    }
}

// --- TEST ---

// 1. On demande l'usine dynamiquement avec juste un string (Comme dans ta Factory Method)
$factory = FactoryProvider::getFactory('CreditCard');

// 2. L'usine nous fabrique les objets de la même famille
$payment = $factory->createPayment();
$receipt = $factory->createReceipt();

// 3. On les utilise !
$payment->processPayment(100);
$receipt->printReceipt(100);

echo "\n";

// Pareil avec PayPal, on change juste le string !
$factory2 = FactoryProvider::getFactory('PayPal');
$factory2->createPayment()->processPayment(50);
$factory2->createReceipt()->printReceipt(50);
