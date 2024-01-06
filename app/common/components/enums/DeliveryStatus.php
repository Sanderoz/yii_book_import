<?php

namespace common\components\enums;

use common\components\traits\EnumValues;
use ReflectionClass;
use ReflectionEnum;

enum DeliveryStatus: int
{
    use EnumValues;
    case NEW = 1;
    case PROCESS = 2;
    case DELIVERY = 3;
    case DELIVERED = 4;
    const NEW_NAME = 'Самовывоз';
    const PROCESS_NAME = 'В процесе сборки';
    const DELIVERY_NAME = 'Передано в службу доставки';
    const DELIVERED_NAME = 'Доставлено';

    /**
     * @param self $type
     * @return string
     */
    public static function getName(self $type): string
    {
        return match ($type) {
            self::NEW => self::NEW_NAME,
            self::PROCESS => self::PROCESS_NAME,
            self::DELIVERY => self::DELIVERY_NAME,
            self::DELIVERED => self::DELIVERED_NAME
        };
    }


}