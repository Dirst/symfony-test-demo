<?php

namespace App\Service;

use App\Dto\CheckoutDto;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CheckoutValidator
{
    public function __construct(protected ValidatorInterface $validator)
    {
    }

    public function validateCheckoutDto(CheckoutDto $checkoutDto): void
    {
        $errors = $this->validator->validate($checkoutDto);
        if (count($errors) == 0) {
            return;
        }

        foreach ($errors as $error) {
            $message[] = $error->getPropertyPath().": ".$error->getMessage();
        }

        throw new BadRequestHttpException(implode(';', $message));
    }
}