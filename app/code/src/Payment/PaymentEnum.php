<?php

namespace App\Payment;

enum PaymentEnum: string
{
    case PAYPAL = 'PAYPAL';
    case STRIPE = 'STRIPE';
}
