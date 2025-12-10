<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Http\Requests\OrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $validated['status'] = OrderStatusEnum::pending()->label;
        $validated['customer_id'] = auth()->id() ?? null;
        $validated = $request->validated();

        Order::create($validated);

        return response()->json(['message' => 'Order placed successfully!']);
    }
}
