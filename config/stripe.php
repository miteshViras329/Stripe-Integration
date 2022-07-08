<?php

return [
    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'currency_code' => env('CURRENCY_CODE'),
    'cancel_url' => env('APP_URL') . '/cancel',
    'success_url' => env('APP_URL') . '/success',
];
