<?php

namespace common\components\enums;

use common\components\traits\EnumValues;

enum DeliveryType: int
{
    use EnumValues;

    case PICKUP = 1;
    case DELIVERY = 2;
    const PICKUP_NAME = 'Самовывоз';
    const DELIVERY_NAME = 'Доставка';

    /**
     * Получение наименования типа доставки
     * @param DeliveryType $type
     * @return string
     */
    public static function getName(self $type): string
    {
        return match ($type) {
            self::PICKUP => self::PICKUP_NAME,
            self::DELIVERY => self::DELIVERY_NAME
        };
    }

    /**
     * Получение enum по значению
     * @param int $type
     * @return DeliveryType|null
     */
    public static function getTypeByValue(int $type): ?DeliveryType
    {
        return match ($type) {
            self::PICKUP->value => self::PICKUP,
            self::DELIVERY->value => self::DELIVERY,
            default => null
        };
    }
}