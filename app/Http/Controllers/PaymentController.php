<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripePaymentService;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct(StripePaymentService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function initiate(Request $request, Order $order)
    {
        $response = $this->stripe->sendPayment($request, $order->id);
        return response()->json([
            'order'   => $order,
            'payment' => $response
        ]);
    }

    public function callback(Request $request)
    {
        $success = $this->stripe->callBack($request);

        return $success
            ? response()->json(['message' => 'Payment success'])
            : response()->json(['message' => 'Payment failed'], 400);
    }

    public function success()
    {

        return view('payment-success');
    }
    public function failed()
    {

        return view('payment-failed');
    }
}
