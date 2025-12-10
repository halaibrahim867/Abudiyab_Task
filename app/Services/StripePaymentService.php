<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Interfaces\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripePaymentService implements PaymentGatewayInterface
{
    protected $api_key;

    public function __construct()
    {
        $this->api_key = env('STRIPE_SECRET_KEY');
        $this->header = [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $this->api_key,
        ];
    }

    /**
     * Initiate Stripe Payment
     */
    public function sendPayment(Request $request, int $orderId): array
    {
        $order = Order::findOrFail($orderId);

        $data = $this->formatData($order);

        $response = Http::withHeaders($this->header)
            ->withOptions(['verify' => false]) // disable SSL verification for local dev
            ->asForm()
            ->post('https://api.stripe.com/v1/checkout/sessions', $data);

        $res = $response->json();

        if (isset($res['id'])) {
            // Save session id in payments table
            Payment::create([
                'order_id'        => $order->id,
                'payment_gateway' => 'stripe',
                'transaction_id'  => $res['id'],
                'amount'          => $order->amount,
                'status'          => 'pending',
                'metadata'        => json_encode(['order_id' => $order->id])
            ]);

            return [
                'success' => true,
                'url'     => $res['url'] ?? route('payment.failed')
            ];
        }

        return [
            'success' => false,
            'url'     => route('payment.failed')
        ];
    }

    /**
     * Stripe callback
     */
    public function callBack(Request $request)
    {
        $session_id = $request->get('session_id');

        $response = Http::withHeaders($this->header)
            ->withOptions(['verify' => false])
            ->get("https://api.stripe.com/v1/checkout/sessions/{$session_id}"); //will change in production , i have problem in ssl

        // $response =$this->buildRequest('POST', '/v1/checkout/sessions', $data, 'form_params');

        $res = $response->json();

        if (!$res || ($res['payment_status'] ?? '') !== 'paid') {
            return false;
        }

        // Update payment & order status
        $payment = Payment::where('transaction_id', $session_id)->first();
        if ($payment) {
            $payment->update(['status' => 'completed']);

            $order = Order::find($payment->order_id);
            if ($order) {
                $order->update(['status' => OrderStatusEnum::completed()->value]);
            }

            return true;
        }

        return false;
    }

    /**
     * Format data for Stripe session
     */
    protected function formatData(Order $order): array
    {
        return [
            'success_url' => url("/api/payments/callback?session_id={CHECKOUT_SESSION_ID}"),
            'cancel_url'  => route('payment.failed'),
            'mode'        => 'payment',
            'line_items'  => [
                [
                    'price_data' => [
                        'currency' => $order->currency,
                        'unit_amount' => $order->amount * 100,
                        'product_data' => [
                            'name' => 'Order #' . $order->id,
                            'description' => 'Payment for order #' . $order->id,
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
        ];
    }
}
