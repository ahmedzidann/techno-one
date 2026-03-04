<?php

namespace App\Enum;

enum AreaType: int
{
    case PROVINCE = 1;
    case CITY = 2;
    case REGION = 3;

    public static function data(): array
    {
        return [
            self::PROVINCE->value => 'محافظة',
            self::CITY->value => 'مدينة',
            self::REGION->value => 'منطقة',
        ];
    }

    public static function label(int $type): string
    {
        $enumType = DeficitType::from($type);

        return match ($enumType) {
            self::PROVINCE => 'محافظة',
            self::CITY => 'مدينة',
            self::REGION => 'منطقة',
        };
    }
}
