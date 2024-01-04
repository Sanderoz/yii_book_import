<?php

namespace common\components\enums;


use ReflectionClass;

enum PaymentType: int
{
    case CARD = 1;
    case SBP = 2;

    /**
     * Получение наименования типа оплаты по enum
     * @param PaymentType $status
     * @return string
     */
    public function getName(self $status): string
    {
        return match ($status) {
            self::CARD => 'Карта',
            self::SBP => 'Система быстрых платежей'
        };
    }

    /**
     * Получение enum по id
     * @param int $id
     * @return PaymentType
     */
    public static function getTypeById(int $id): PaymentType
    {
        return match ($id) {
            self::CARD->value => self::CARD,
            self::SBP->value => self::SBP
        };
    }

    public static function getValues(): array
    {
        $reflection = new ReflectionClass(self::class);
        return array_values($reflection->getConstants());
    }
}