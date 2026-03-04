<?php

namespace App\Enum;

enum ChequeStatus: int {
    case IN_PROGRESS = 1;
    case ACCEPTED = 2;
    case REFUSED = 3;

    public static function data(): array
    {
        return [
            self::IN_PROGRESS->value => 'جاري التنفيذ',
            self::ACCEPTED->value => 'تم الصرف',
            self::REFUSED->value => 'مرفوض',
        ];
    }

    public static function label(int $type): string
    {
        $enumType = DeficitType::from($type);

        return match ($enumType) {
            self::IN_PROGRESS => 'جاري التنفيذ',
            self::ACCEPTED => 'تم الصرف',
            self::REFUSED => 'مرفوض',
        };
    }

}
