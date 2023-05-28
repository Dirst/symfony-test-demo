<?php

namespace App\Payment;

enum PaymentEnum: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
