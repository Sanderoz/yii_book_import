<?php

namespace common\components\enums;

use common\components\traits\EnumValues;
use ReflectionClass;

enum PaymentStatus: int
{
    use EnumValues;

    case NEW = 1;
    case PROCESSED = 2;
    case CANCELED = 3;
    case PAID = 4;

    /**
     * Статус по-русски
     * @param PaymentStatus $status
     * @return string
     */
    public function getName(self $status): string
    {
        return match ($status) {
            self::NEW => 'Новый',
            self::PROCESSED => 'В процессе обработки',
            self::CANCELED => 'Отменен',
            self::PAID => 'Оплачен',
        };
    }

    /**
     * Получение статуса по id
     * @param int $status
     * @return PaymentStatus|null
     */
    public static function getByValue(int $status): ?PaymentStatus
    {
        return match ($status) {
            self::NEW->value => self::NEW,
            self::PROCESSED->value => self::PROCESSED,
            self::CANCELED->value => self::CANCELED,
            self::PAID->value => self::PAID,
            default => null
        };
    }
}