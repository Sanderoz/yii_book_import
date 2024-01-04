<?php

namespace common\components\enums;

use ReflectionClass;

enum OrderStatus: int
{
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

    public static function getValues(): array
    {
        $reflection = new ReflectionClass(self::class);
        return array_values($reflection->getConstants());
    }

}