<?php

namespace App\Controller;

use App\Dto\CheckoutDto;
use App\Payment\PaymentProcessorFactory;
use App\Service\CheckoutPriceResolver;
use App\Service\RequestCheckoutParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @TODO extract services to application layer from actions.
 */
class ApiController extends AbstractController
{
    public function __construct(
        public readonly RequestCheckoutParser $requestCheckoutParser,
        public readonly CheckoutPriceResolver $checkoutPriceResolver,
        public readonly ValidatorInterface $validator,
        public readonly PaymentProcessorFactory $paymentProcessorFactory
    ) {
    }

    #[Filters('productId*', 'taxNumber*', 'couponCode')]
    #[Route('/get-price', methods: ['GET'])]
    public function getPrice(Request $request): JsonResponse
    {
        try {
            $checkoutDto = $this->requestCheckoutParser->getCheckoutDtoFromGetRequest($request);
            $this->validateCheckoutDto($checkoutDto);
            $price = $this->checkoutPriceResolver->getPriceAfterDeductions($checkoutDto);

            return $this->json(['price' => round($price, 1)]);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 400);
        }
    }

    #[JsonContent('productId*', 'taxNumber*', 'couponCode', 'paymentProcessor*')]
    #[Route('/pay', methods: ['POST'])]
    public function pay(Request $request): JsonResponse
    {
        try {
            $checkoutDto = $this->requestCheckoutParser->getCheckoutDtoFromPostRequest($request);
            $this->validateCheckoutDto($checkoutDto);
            $this->executePayment($checkoutDto);

            return $this->json(['message' =>'payment success']);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 400);
        }
    }

    /**
     * @TODO need to remove it from presentation layer into Application layer.
     */
    protected function executePayment(CheckoutDto $checkoutDto): void
    {
        $price = $this->checkoutPriceResolver->getPriceAfterDeductions($checkoutDto);

        $paymentProcessor = $this->paymentProcessorFactory->get($checkoutDto->paymentProcessor);
        $paymentProcessor->pay($price);
    }

    /**
     * @TODO need to remove it from presentation layer into Application layer.
     */
    protected function validateCheckoutDto(CheckoutDto $checkoutDto): void
    {
        $errors = $this->validator->validate($checkoutDto);
        if (count($errors) == 0) {
            return;
        }

        foreach ($errors as $error) {
            $message[] = $error->getPropertyPath() .": ". $error->getMessage();
        }

        throw new \Exception(implode(';', $message));
    }
}
