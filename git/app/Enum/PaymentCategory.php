<?php

namespace App\Enum;

enum PaymentCategory: int {

    case EVERY_MONTH = 1;
    case EVERY_15_DAYS = 2;
    case EVERY_WEEK = 3;
    case ON_DELIVERED = 4;

    // Optional: You can create a method to return human-readable labels
    public function label(): string
    {
        return match ($this) {
            self::EVERY_MONTH => 'Every Month',
            self::EVERY_15_DAYS => 'Every 15 Days',
            self::EVERY_WEEK => 'Every Week',
            self::ON_DELIVERED => 'On Delivered',
        };
    }

    // Return an array of categories
    public static function getCategories(): array
    {
        return [
            self::EVERY_MONTH,
            self::EVERY_15_DAYS,
            self::EVERY_WEEK,
            self::ON_DELIVERED,
        ];
    }

    public static function getCategoriesSelect(): array
    {
        return [
            self::EVERY_MONTH->value => 'كل شهر',
            self::EVERY_15_DAYS->value => 'كل 15 يوم',
            self::EVERY_WEEK->value => 'كل أسبوع',
            self::ON_DELIVERED->value => 'عند التوصيل',
        ];
    }
}
