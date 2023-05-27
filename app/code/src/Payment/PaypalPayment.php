<?php

namespace App\Payment;

use App\Payment\PaymentLibrary\PaypalPaymentProcessor;

/*
 * @NOTICE Adapter for PaypalPaymentProcessor
 */
class PaypalPayment implements PaymentProcessorInterface
{
    public function __construct(public readonly PaypalPaymentProcessor $paypalPayment)
    {
    }

    public function pay(float $price): void
    {
        $this->paypalPayment->pay((int) $price);
    }
}
