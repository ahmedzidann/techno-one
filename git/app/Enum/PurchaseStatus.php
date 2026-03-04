<?php

namespace App\Enum;

enum PurchaseStatus: int {
    case IN_PROGRESS = 1;
    case COMPLETED = 2;

    public static function data(): array
    {
        return [
            self::IN_PROGRESS->value => 'طلب شراء',
            self::COMPLETED->value => 'فاتورة مشتريات',
        ];
    }

    public static function label(int $type): string
    {
        return match ($enumType) {
            self::IN_PROGRESS => 'طلب شراء',
            self::COMPLETED => 'فاتورة مشتريات',
        };
    }

}
