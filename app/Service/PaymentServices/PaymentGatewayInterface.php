<?php
namespace App\Service\PaymentServices;

interface PaymentGatewayInterface
{
    /**
     * Charge a payment method.
     *
     * @param array $data Data necessary for the charge.
     * @return mixed
     */
    public function charge(array $data);



    /**
     * Refund a previously made charge.
     *
     * @param string $transactionId The ID of the transaction to refund.
     * @param array $options Additional options for the refund.
     * @return mixed
     */
    public function refund(string $transactionId, array $options = []);



    /**
     * Create a new subscription.
     *
     * @param array $customerData Data about the customer subscribing.
     * @param array $subscriptionData Data about the subscription itself.
     * @return mixed
     */
    public function subscribe(array $customerData, array $subscriptionData);



    /**
     * Cancel an existing subscription.
     *
     * @param string $subscriptionId The ID of the subscription to cancel.
     * @return mixed
     */
    public function cancelSubscription(string $subscriptionId);



    /**
     * Update an existing subscription.
     *
     * @param string $subscriptionId The ID of the subscription to update.
     * @param array $updateData Data for updating the subscription.
     * @return mixed
     */
    public function updateSubscription(string $subscriptionId, array $updateData);



    /**
     * Create or update a customer profile in the payment gateway.
     *
     * @param array $customerData Data about the customer.
     * @return mixed
     */
    public function createOrUpdateCustomer(array $customerData);



    /**
     * Get the details of a specific charge.
     *
     * @param string $transactionId The ID of the transaction.
     * @return mixed
     */
    public function getTransactionDetails(string $transactionId);



    /**
     * Validate a payment method (like a card) without charging it.
     *
     * @param array $paymentMethodData Data about the payment method to validate.
     * @return mixed
     */
    public function validatePaymentMethod(array $paymentMethodData);


    
}
