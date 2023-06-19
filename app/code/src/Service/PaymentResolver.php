<?php

namespace App\Service;

use App\Dto\CheckoutDto;
use App\Payment\PaymentProcessorFactory;

class PaymentResolver
{
    public function __construct(
        protected readonly CheckoutPriceResolver $priceResolver,
        protected readonly PaymentProcessorFactory $processorFactory
    ) {
    }

    public function executePayment(CheckoutDto $checkoutDto): void
    {
        $price = $this->priceResolver->getPriceAfterDeductions($checkoutDto);

        $paymentProcessor = $this->processorFactory->get($checkoutDto->paymentProcessor);
        $paymentProcessor->pay(round($price));
    }
}