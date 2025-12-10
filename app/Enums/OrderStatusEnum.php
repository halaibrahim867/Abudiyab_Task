<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

final class OrderStatusEnum extends Enum
{
    public static function values(): array
    {
        return [
            'pending' => 'pending',
            'processing' => 'processing',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
        ];
    }

    public static function translatedValues(): array
    {
        return [
            'pending' => __('Pending'),
            'processing' => __('Processing'),
            'completed' => __('Completed'),
            'cancelled' => __('Cancelled'),
            'refunded' => __('Refunded'),
        ];
    }
}
