<?php

namespace App\DataFixtures;

use App\Entity\CountryTax;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['iphone' => 100, "Наушники" => 20, 'Чехол' => 10] as $name => $price) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);

            $manager->persist($product);
        }

        $fixed = true;
        foreach (['COUPON1' => 20, 'COUPON2' => 5] as $value => $discount) {
            $coupon = new Coupon();
            $coupon->setValue($value);
            $coupon->setDiscount($discount);
            $coupon->setIsFixedDiscount($fixed);
            $fixed = !$fixed;

            $manager->persist($coupon);
        }

        foreach (['DE' => 19, 'IT' => 22, 'GR' => 24] as $code => $tax) {
            $countryTax = new CountryTax();
            $countryTax->setTax($tax);
            $countryTax->setCode($code);

            $manager->persist($countryTax);
        }

        $manager->flush();
    }
}
