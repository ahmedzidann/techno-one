<?php

namespace App\Enum;

enum RepresentativeType: int
{
    case REPRESENTATIVE = 1;
    case DISTRIBUTOR = 2;

    public static function data(): array
    {
        return [
            self::REPRESENTATIVE->value => 'مندوب',
            self::DISTRIBUTOR->value => 'موزع',
        ];
    }

    public static function label(int $type): string
    {
        $enumType = DeficitType::from($type);

        return match ($enumType) {
            self::REPRESENTATIVE => 'مندوب',
            self::DISTRIBUTOR => 'موزع',
        };
    }
}
