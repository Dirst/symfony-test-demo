<?php

namespace App\ValueResolver;

use App\Service\RequestCheckoutParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CheckoutDtoResolver implements ValueResolverInterface
{

    public function __construct(protected readonly RequestCheckoutParser $requestCheckoutParser)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($request->getMethod() === Request::METHOD_GET) {
            $checkoutDto = $this->requestCheckoutParser->getCheckoutDtoFromGetRequest($request);

            return [$checkoutDto];
        }

        $checkoutDto = $this->requestCheckoutParser->getCheckoutDtoFromPostRequest($request);

        return [$checkoutDto];
    }
}