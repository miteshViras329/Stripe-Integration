<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;

class PaymentController extends Controller
{
    protected $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    public function store()
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
}
