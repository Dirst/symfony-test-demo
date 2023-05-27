<?php

namespace App\Tests;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetPriceEndpointWebTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testParametersAbsenceErrors(): void
    {
        $this->client->request('GET', '/get-price');

        $this->assertEquals(
            '{"message":"Product ID or Tax Number has not been passed in GET request"}',
            $this->client->getResponse()->getContent()
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testParametersValidation(): void
    {
        $this->client->request('GET', '/get-price?productId=1&taxNumber=21231&couponCode=CODE');

        $this->assertEquals('{"message":"taxCode: This value is not valid."}', $this->client->getResponse()->getContent());
    }

    public function testPriceCalcFixedCoupon(): void
    {
        $this->client->request('GET', '/get-price?productId=1&taxNumber=DE121212&couponCode=COUPON1');

        $priceExpected = (100 - 20) * 1.19;

        $json = $this->client->getResponse()->getContent();
        $this->assertEquals(round($priceExpected, 1), json_decode($json, true)['price']);
    }

    public function testPriceCalcPercentCoupon(): void
    {
        $this->client->request('GET', '/get-price?productId=2&taxNumber=IT121212&couponCode=COUPON2');

        $priceExpected = (20 * 0.95) * 1.22;

        $json = $this->client->getResponse()->getContent();
        $this->assertEquals(round($priceExpected, 1), json_decode($json, true)['price']);
    }
}
