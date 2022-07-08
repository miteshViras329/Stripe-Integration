<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;

class PriceController extends Controller
{
    protected $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    public function store()
    {
        $data = request()->validate([
            'product' => 'required',
            'unit_amount' => 'required|numeric',
        ]);
        $response = $this->stripe->prices->create([
            'unit_amount' => $data['unit_amount'],
            'unit_amount' => $data['unit_amount'],
            'currency' => config('stripe.currency_code'),
            'product' => $data['product'],
        ]);

        if (empty($response)) {
            return response()->json(['Error' => 'Price Not Created!']);
        }

        return $response;
    }

    public function update($price_id)
    {
        $data = request()->validate([
            'product' => 'required',
            'unit_amount' => 'required|numeric',
            'recurring' => 'nullable|array',
        ]);

        $response = $this->stripe->prices->update(
            $price_id,
            ['metadata' => ['order_id' => '6735']]
        );

        if (empty($response)) {
            return response()->json(['Error' => 'Price Not Created!']);
        }

        return $response;
    }



    public function show($price_id)
    {
        $response = $this->stripe->prices->retrieve(
            $price_id
        );

        if (empty($response)) {
            return response()->json(['Error' => 'Price Not Found!']);
        }

        return $response;
    }
}
