<?php
namespace App\Factories\PaymentFactory;

use App\Service\PaymentServices\PaymentGatewayInterface;
use App\Service\PaymentServices\Paymob\PaymobPaymentService;
use App\Service\PaymentServices\Stripe\StripePaymentService;

class PaymentGatewayFactory {


    public static function create(string $gateway) : PaymentGatewayInterface{
        switch ($gateway) {
            case 'stripe':
                return new StripePaymentService();
            case 'paymob':
                return new PaymobPaymentService();
            // Add cases for other gateways
            default:
                throw new \Exception("Payment gateway {$gateway} is not supported.");
        }
    }
}

