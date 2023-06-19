<?php

namespace App\Controller;

use App\Dto\CheckoutDto;
use App\Service\CheckoutPriceResolver;
use App\Service\CheckoutValidator;
use App\Service\PaymentResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(
        public readonly CheckoutPriceResolver $checkoutPriceResolver,
        public readonly CheckoutValidator $validator,
        public readonly PaymentResolver $paymentResolver
    ) {
    }

    #[Filters('productId*', 'taxNumber*', 'couponCode')]
    #[Route('/get-price', methods: ['GET'])]
    public function getPrice(CheckoutDto $checkoutDto): JsonResponse
    {
        $this->validator->validateCheckoutDto($checkoutDto);
        $price = $this->checkoutPriceResolver->getPriceAfterDeductions($checkoutDto);

        return $this->json(['price' => round($price, 1)]);
    }

    #[JsonContent('productId*', 'taxNumber*', 'couponCode', 'paymentProcessor*')]
    #[Route('/pay', methods: ['POST'])]
    public function pay(CheckoutDto $checkoutDto): JsonResponse
    {
        $this->validator->validateCheckoutDto($checkoutDto);
        $this->paymentResolver->executePayment($checkoutDto);

        return $this->json(['message' => 'payment success']);
    }

}
