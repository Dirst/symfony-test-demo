<?php

namespace App\Service;

use App\Dto\CheckoutDto;
use App\Entity\CountryTax;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CheckoutPriceResolver
{
    public function __construct(
        public readonly ProductPriceCalculator $productPriceCalculator,
        public readonly EntityManagerInterface $entityManager
    ) {
    }

    public function getPriceAfterDeductions(CheckoutDto $checkout): float
    {
        $productEntity = $this->getProductById($checkout->productId);
        $countryTaxEntity = $this->getCountryTax($checkout->taxCode);

        if ($checkout->coupon) {
            $couponEntity = $this->entityManager->getRepository(Coupon::class)->findOneBy(['value' => $checkout->coupon]);
        }

        $price = $this->productPriceCalculator->getProductPriceAfterDeductions(
            $productEntity,
            $countryTaxEntity,
            $couponEntity ?? null
        );

        // Assume minimum price is 1EUR;
        return $price < 0 ? 1 : $price;
    }

    protected function getProductById(int $productId): Product
    {
        $productEntity = $this->entityManager->getRepository(Product::class)->find($productId);
        if (!$productEntity) {
            throw new \Exception("Can't find product for id = $productId");
        }

        return $productEntity;
    }

    protected function getCountryTax(string $taxCode): CountryTax
    {
        $countryCode = $this->getCountryCodeInTaxNumber($taxCode);
        $countryTaxEntity = $this->entityManager->getRepository(CountryTax::class)->findOneBy(['code' => $countryCode]);
        if (!$countryTaxEntity) {
            throw new \Exception("Can't find countryTax for code = $countryCode");
        }

        return $countryTaxEntity;
    }

    protected function getCountryCodeInTaxNumber(string $taxCode): string
    {
        return substr($taxCode, 0, 2);
    }
}
