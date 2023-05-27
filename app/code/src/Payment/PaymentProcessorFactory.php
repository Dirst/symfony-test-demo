<?php

namespace App\Payment;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class PaymentProcessorFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function get(PaymentEnum $paymentType): PaymentProcessorInterface
    {
        $paymentTypeCamelCase = ucwords(strtolower($paymentType->value));

        $paymentProcessor = $this->container->get("App\\Payment\\{$paymentTypeCamelCase}Payment");

        if (!$paymentProcessor instanceof PaymentProcessorInterface) {
            throw new \Exception(
                "$paymentType payment processor can't be loaded, it should be instance of PaymentProcessorInterface"
            );
        }

        return $paymentProcessor;
    }
}
