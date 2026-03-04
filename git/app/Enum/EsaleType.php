<?php

namespace App\Enum;

enum EsaleType: int {
    case ESALE = 1;
    case CHEQUE = 2;

    public static function data(): array
    {
        return [
            self::ESALE->value => 'إيصال',
            self::CHEQUE->value => 'شيك',
        ];
    }

    public static function label(int $type): string
    {
        $enumType = DeficitType::from($type);

        return match ($enumType) {
            self::ESALE => 'إيصال',
            self::CHEQUE => 'شيك',
        };
    }

}
