<?php

namespace common\components\enums;

enum OrderStatus: int
{
    case DELETED = 0;
    case NEW = 1;
    case PROCESSED = 2;
    case CANCELED = 3;
    case PAID = 4;

    public function getName(self $status): string
    {
        return match ($status) {
            self::DELETED => 'Удален',
            self::NEW => 'Новый',
            self::PROCESSED => 'В процессе обработки',
            self::CANCELED => 'Отменен',
            self::PAID => 'Оплачен',
        };
    }

}