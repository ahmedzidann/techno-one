<?php

namespace App\Enum;

enum DeficitType: int {
    case INCREMENT = 1;
    case DECREMENT = 2;

    public static function data(): array
    {
        return [
            self::INCREMENT->value => 'زيادة',
            self::DECREMENT->value => 'نقص',
        ];
    }

    public static function label(int $type): string
    {
        $enumType = DeficitType::from($type);

        return match ($enumType) {
            self::INCREMENT => 'زيادة',
            self::DECREMENT => 'نقص',
        };
    }

}
