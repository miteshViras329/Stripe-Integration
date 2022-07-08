<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;

class ProductController extends Controller
{
    protected $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['active'] = !empty($data['active']) ? $data['active'] : true;

        if (empty($data['description'])) {
            unset($data['description']);
        }

        $response = $this->stripe->products->create([
            'name' => $data['name'],
        ] + $data);

        if (empty($response)) {
            return response()->json(['Error' => 'Product Not Created!']);
        }

        return $response;
    }

    public function show($id)
    {
        $response = $this->stripe->products->retrieve(
            $id,
            []
        );
        if (empty($response)) {
            return response()->json(['Error' => 'Product Not Found!']);
        }
        return $response;
    }

    public function index()
    {
        $response = $this->stripe->products->all(['limit' => request()->per_page ?? 10]);
        if (empty($response)) {
            return response()->json(['Error' => 'Product Not Found!']);
        }
        return $response;
    }
}
