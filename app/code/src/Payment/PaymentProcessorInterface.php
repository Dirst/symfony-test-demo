<?php

namespace App\Payment;

interface PaymentProcessorInterface
{
    public function pay(float $price): void;
}
