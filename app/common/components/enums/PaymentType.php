<?php

namespace common\components\enums;


use common\components\traits\EnumValues;
use ReflectionClass;

enum PaymentType: int
{
    use EnumValues;

    case CARD = 1;
    case SBP = 2;
    const CARD_NAME = 'Оплата картой';
    const SBP_NAME = 'Оплата с помощью СБП';

    /**
     * @return array
     */
    public static function getKeyValue(): array
    {
        return [
            self::CARD->value => self::CARD_NAME,
            self::SBP->value => self::SBP_NAME
        ];
    }

    /**
     * Получение enum по id
     * @param int $id
     * @return PaymentType
     */
    public static function getTypeByValue(int $id): ?PaymentType
    {
        return match ($id) {
            self::CARD->value => self::CARD,
            self::SBP->value => self::SBP,
            default => null
        };
    }

}