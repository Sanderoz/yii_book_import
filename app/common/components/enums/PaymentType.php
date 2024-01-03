<?php

namespace common\components\enums;

enum PaymentType: int
{
    case CARD = 1;
    case SBP = 2;

    public function getName(self $status): string
    {
        return match ($status) {
            self::CARD => 'Карта',
            self::SBP => 'Система быстрых платежей'
        };
    }
}