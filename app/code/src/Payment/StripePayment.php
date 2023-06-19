<?php

namespace App\Payment;

use App\Payment\PaymentLibrary\StripePaymentProcessor;

/*
 * @NOTICE Adapter for StripePaymentProcessor
 */
class StripePayment implements PaymentProcessorInterface
{
    public function __construct(public readonly StripePaymentProcessor $stripePayment)
    {
    }

    public function pay(int $price): void
    {
        $paymentResult = $this->stripePayment->processPayment($price);

        // Adapt for explicit erroring.
        if (!$paymentResult) {
            throw new \Exception('Too high price');
        }
    }
}
