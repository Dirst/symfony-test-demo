<?php

namespace App\Dto;

use App\Payment\PaymentEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CheckoutDto
{
    public function __construct(
        #[Assert\Type('int')]
        public readonly int $productId,
        #[Assert\Regex('/([A-Z]{2})([A-Z]*)(\d+)/')]
        public readonly string $taxCode,
        #[Assert\Type('string')]
        public readonly string $coupon = '',
        public readonly ?PaymentEnum $paymentType = null
    ) {
    }
}
