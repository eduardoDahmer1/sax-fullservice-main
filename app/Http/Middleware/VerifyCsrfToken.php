<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/checkout/payment/notify',
        '/user/payment/notify',
        '/paytm-callback',
        '/the/genius/ocean/2441139',
        '/user/paytm/notify',
        '/razorpay-callback',
        '/user/razorpay/notify',
        'admin/shipping_prices/getStates',
        '/bancard-callback',
        '/bancard-rollback/*',
        '/checkout/getStatesOptions',
        '/checkout/getCitiesOptions',
        '/checkout/getShippingsOptions',
        '/checkout/getCorreios',
        '/cielo-callback',
        '/pagarme-callback',
        '/pagseguro-callback',
        '/pagopar/callback',
        '/pagopar/check-order-status/*',
        '/rede-callback',
        '/paypal-callback',
        'admin/category/delete-image'
    ];
}
