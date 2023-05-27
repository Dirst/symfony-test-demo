<?php

namespace App\Service;

use App\Dto\CheckoutDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class RequestCheckoutParser
{
    public function __construct(public readonly SerializerInterface $serializer)
    {
    }

    public function getCheckoutDtoFromGetRequest(Request $request): CheckoutDto
    {
        $productId = $request->query->get('productId');
        $taxNumber = $request->query->get('taxNumber');

        if (!$productId or !$taxNumber) {
            throw new \Exception("Product ID or Tax Number has not been passed in GET request");
        }

        if (!is_numeric($productId)) {
            throw new \Exception("Product ID should be integer");
        }

        $coupon = $request->query->get('couponCode') ?? '';

        return new CheckoutDto($productId, $taxNumber, $coupon);
    }

    public function getCheckoutDtoFromPostRequest(Request $request): CheckoutDto
    {   
        return $this->serializer->deserialize($request->getContent(), CheckoutDto::class, 'json');
    }
}
