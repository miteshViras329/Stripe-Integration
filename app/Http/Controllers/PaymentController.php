<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;

use function PHPUnit\Framework\throwException;

class PaymentController extends Controller
{
    public $stripeDocUrl = 'https://stripe.com/docs/api/checkout/sessions';
    protected $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    public function checkout()
    {
        $data = request()->validate([
            'amount' => 'required|numeric',
        ]);

        $response = $this->stripe->checkout->sessions->create([
            'success_url' => config('stripe.success_url'),
            'cancel_url' => config('stripe.cancel_url'),
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => config('stripe.currency_code'),
                        'product_data' => [
                            'name' => 'skyrush'
                        ],
                        'unit_amount' => $data['amount'] * 100,
                    ]
                ],
            ],
            'mode' => 'payment',
        ]);

        if (empty($response)) {
            return response()->json(['Error' => 'Session Not Created!']);
        }

        return $response;
    }

    public function subscription()
    {
        $data = request()->validate([
            'amount' => 'required|numeric',
            'name' => 'required|string',
            'interval' => 'required',
        ]);

        if (!in_array($data['interval'], ['month', 'year'])) {
            return 'Interval Must Be day,week,month or year';
        }

        $response = $this->stripe->checkout->sessions->create([
            'success_url' => config('stripe.success_url'),
            'cancel_url' => config('stripe.cancel_url'),
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => config('stripe.currency_code'),
                        'product_data' => [
                            'name' => $data['name'],
                        ],
                        'unit_amount' => $data['amount'] * 100,
                        'recurring' => [
                            'interval' => $data['interval'],
                            'interval_count' => 1
                        ]
                    ]
                ],
            ],
            'mode' => 'subscription',
        ]);

        if (empty($response)) {
            return response()->json(['Error' => 'Session Not Created!']);
        }

        return $response;
    }

    public function show($id)
    {
        $response = $this->stripe->checkout->sessions->retrieve(
            $id,
            []
        );
        if (empty($response)) {
            return response()->json(['Error' => 'Session Not Found!']);
        }

        return $response;
    }

    public function showSubscription($id)
    {
        $response =  $this->stripe->subscriptions->retrieve(
            $id,
            []
        );

        if (empty($response)) {
            return response()->json(['Error' => 'Subscription Not Found!']);
        }

        return $response;
    }
}
