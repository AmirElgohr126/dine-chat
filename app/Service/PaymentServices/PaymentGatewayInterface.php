<?php
namespace App\Service\PaymentServices;

interface PaymentGatewayInterface
{
    public function charge(array $data);
    // Add other common methods, e.g., refund, subscribe, etc.
}
