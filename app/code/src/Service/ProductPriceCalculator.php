<?php

namespace App\Service;

use App\Entity\CountryTax;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductPriceCalculator
{

    public function getProductPriceAfterDeductions(Product $product, CountryTax $countryTax, Coupon $coupon = null): float
    {
        $price = $product->getPrice();

        if ($coupon) {
            $price = $this->getProductPriceByCoupon($price, $coupon);
        }

        return $this->getPriceAfterTaxes($price, $countryTax);
    }

    public function getProductPriceByCoupon(float $price, Coupon $coupon): float
    {
        $couponSaleValue = $coupon->getDiscount();

        if ($coupon->isFixedDiscount()) {
            return $price -= $couponSaleValue;
        }

        return $price -= $price * ($couponSaleValue / 100);
    }

    public function getPriceAfterTaxes(float $price, CountryTax $countryTax): float
    {
        return $price += $price * ($countryTax->getTax() / 100);
    }
}
