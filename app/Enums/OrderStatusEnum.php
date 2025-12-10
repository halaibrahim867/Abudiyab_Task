<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;
/**
 * @method static self pending()
 * @method static self processing()
 * @method static self completed()
 * @method static self cancelled()
 * @method static self refunded()
 */
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
