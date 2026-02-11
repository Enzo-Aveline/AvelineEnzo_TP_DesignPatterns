<?php

interface Payment
{
    public function processPayment($amount);
}

class CreditCardPayment implements Payment
{
    public function processPayment($amount)
    {
        echo "Processing credit card payment of $amount\n";
    }
}

class PayPalPayment implements Payment
{
    public function processPayment($amount)
    {
        echo "Processing PayPal payment of $amount\n";
    }
}

class PaymentFactory
{
    public static function createPayment($type, $amount)
    {
        $classname = $type . "Payment";
        $payment = new $classname();
        $payment->processPayment($amount);
    }
}

PaymentFactory::createPayment('CreditCard', 100);

