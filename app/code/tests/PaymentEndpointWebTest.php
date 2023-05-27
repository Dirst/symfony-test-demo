<?php

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentEndpointWebTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPostParametersAbsenceErrors(): void
    {
        $this->client->request('POST', '/pay', [], [], [], '{}');

        $this->assertEquals(
            '{"message":"Cannot create an instance of \u0022App\\\Dto\\\CheckoutDto\u0022 from serialized data because its constructor requires parameter \u0022productId\u0022 to be present."}',
            $this->client->getResponse()->getContent()
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPostParametersValidation(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/pay',
            [
                "productId" => 1,
                "taxCode" => "TAX",
                "coupon" => "COUPON",
                "paymentType" => "PAYPAL"
            ]
        );

        $this->assertEquals(
            '{"message":"taxCode: This value is not valid."}',
            $this->client->getResponse()->getContent()
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPaymentSuccess(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/pay',
            [
                "productId" => 1,
                "taxCode" => "DE111222333",
                "coupon" => "COUPON1",
                "paymentType" => "STRIPE"
            ]
        );

        $this->assertEquals(
            '{"message":"payment success"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testPaymentTooHighPricePaypal(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/pay',
            [
                "productId" => 1,
                "taxCode" => "DE111222333",
                "coupon" => "COUPON2",
                "paymentType" => "PAYPAL"
            ]
        );

        $this->assertEquals(
            '{"message":"Too high price"}',
            $this->client->getResponse()->getContent()
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}
