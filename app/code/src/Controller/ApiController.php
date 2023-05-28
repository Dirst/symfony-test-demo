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
    #[Filters('productId*', 'taxNumber*', 'couponCode')]
    #[Route('/get-price', methods: ['GET'])]
    public function getPrice(
        Request $request,
        RequestCheckoutParser $requestCheckoutParser,
        CheckoutPriceResolver $checkoutPriceResolver,
        ValidatorInterface $validator,
    ): JsonResponse {
        try {
            $checkoutDto = $requestCheckoutParser->getCheckoutDtoFromGetRequest($request);
            $this->validateCheckoutDto($validator, $checkoutDto);
            $price = $checkoutPriceResolver->getPriceAfterDeductions($checkoutDto);

            return $this->json(['price' => round($price, 1)]);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 400);
        }
    }

    #[JsonContent('productId*', 'taxNumber*', 'couponCode', 'paymentProcessor*')]
    #[Route('/pay', methods: ['POST'])]
    public function pay(
        Request $request,
        PaymentProcessorFactory $paymentProcessorFactory,
        CheckoutPriceResolver $checkoutPriceResolver,
        RequestCheckoutParser $requestCheckoutParser,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $checkoutDto = $requestCheckoutParser->getCheckoutDtoFromPostRequest($request);
            $this->validateCheckoutDto($validator, $checkoutDto);
            $this->executePayment($checkoutDto, $paymentProcessorFactory, $checkoutPriceResolver);

            return $this->json(['message' =>'payment success']);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 400);
        }
    }

    /**
     * @TODO need to remove it from presentation layer into Application layer.
     */
    protected function executePayment(
        CheckoutDto $checkoutDto,
        PaymentProcessorFactory $paymentProcessorFactory,
        CheckoutPriceResolver $checkoutPriceResolver
    ): void {
        $price = $checkoutPriceResolver->getPriceAfterDeductions($checkoutDto);

        $paymentProcessor = $paymentProcessorFactory->get($checkoutDto->paymentProcessor);
        $paymentProcessor->pay($price);
    }

    /**
     * @TODO need to remove it from presentation layer into Application layer.
     */
    protected function validateCheckoutDto(ValidatorInterface $validator, CheckoutDto $checkoutDto): void
    {
        $errors = $validator->validate($checkoutDto);
        if (count($errors) == 0) {
            return;
        }

        foreach ($errors as $error) {
            $message[] = $error->getPropertyPath() .": ". $error->getMessage();
        }

        throw new \Exception(implode(';', $message));
    }
}
