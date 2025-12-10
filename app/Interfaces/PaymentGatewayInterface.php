<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{

    public function sendPayment(Request $request, int $orderId): array;

    public function callBack(Request $request);
}
