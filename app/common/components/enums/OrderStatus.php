<?php

namespace common\components\enums;

use common\components\traits\EnumValues;
use ReflectionEnum;

enum OrderStatus: int
{
    use EnumValues;
    case DELETED = 0;
    case NEW = 1;
    case PROCESSED = 2;
    case CANCELED = 3;
    case PAID = 4;
    case CONFIRMED = 5;

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