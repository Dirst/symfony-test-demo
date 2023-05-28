<?php

namespace App\Tests;

use App\Dto\CheckoutDto;
use App\Payment\PaymentEnum;
use App\Service\RequestCheckoutParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class AppKernelTest extends KernelTestCase
{
    protected RequestCheckoutParser $requestCheckoutParser;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->requestCheckoutParser = $container->get(RequestCheckoutParser::class);
    }

    public function testRequestGetDeserializer(): void
    {
        $request = new Request(['productId' => 1, 'taxNumber' => 'TAXNUMBER', 'couponCode' => 'COUPON']);

        $dto = $this->requestCheckoutParser->getCheckoutDtoFromGetRequest($request);

        $this->assertEquals(new CheckoutDto(1, 'TAXNUMBER', 'COUPON'), $dto);
    }

    public function testRequestPostDeserializer(): void
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            '{"productId": 1, "taxCode": "TAX", "coupon": "COUPON", "paymentProcessor": "paypal"}'
        );

        $dto = $this->requestCheckoutParser->getCheckoutDtoFromPostRequest($request);
        $this->assertEquals(new CheckoutDto(1, 'TAX', 'COUPON', PaymentEnum::from("paypal")), $dto);
    }
}
