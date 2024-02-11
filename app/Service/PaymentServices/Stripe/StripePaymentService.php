<?php
namespace App\Service\PaymentServices\Stripe;

use Stripe\Stripe;
use App\Service\PaymentServices\PaymentGatewayInterface;


class StripePaymentService implements PaymentGatewayInterface{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }


    /**
     * Cancel an existing subscription.
     *
     * @param string $subscriptionId The ID of the subscription to cancel.
     */
    function cancelSubscription(string $subscriptionId) {
    }

    /**
     * Charge a payment method.
     *
     * @param array $data Data necessary for the charge.
     */
    function charge(array $data) {
    }

    /**
     * Create or update a customer profile in the payment gateway.
     *
     * @param array $customerData Data about the customer.
     */
    function createOrUpdateCustomer(array $customerData) {
    }

    /**
     * Get the details of a specific charge.
     *
     * @param string $transactionId The ID of the transaction.
     */
    function getTransactionDetails(string $transactionId) {
    }

    /**
     * Refund a previously made charge.
     *
     * @param string $transactionId The ID of the transaction to refund.
     * @param array $options Additional options for the refund.
     */
    function refund(string $transactionId, array $options = []) {
    }

    /**
     * Create a new subscription.
     *
     * @param array $customerData Data about the customer subscribing.
     * @param array $subscriptionData Data about the subscription itself.
     */
    function subscribe(array $customerData, array $subscriptionData) {
    }

    /**
     * Update an existing subscription.
     *
     * @param string $subscriptionId The ID of the subscription to update.
     * @param array $updateData Data for updating the subscription.
     */
    function updateSubscription(string $subscriptionId, array $updateData) {
    }

    /**
     * Validate a payment method (like a card) without charging it.
     *
     * @param array $paymentMethodData Data about the payment method to validate.
     */
    function validatePaymentMethod(array $paymentMethodData) {
    }
}
