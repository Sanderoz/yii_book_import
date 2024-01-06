<?php

namespace common\components\enums;

use common\components\traits\EnumValues;

enum BookStatus: string
{
    use EnumValues;
    case PUBLISH = 'publish';
    case MEAP = 'meap';
    case DEFAULT = 'empty';

    public static function getStatus(string $status): string
    {
        return match ($status) {
            BookStatus::MEAP->name => BookStatus::MEAP->value,
            BookStatus::PUBLISH->name => BookStatus::PUBLISH->value,
            default => BookStatus::DEFAULT
        };
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            self::MEAP->value => self::MEAP->value,
            self::PUBLISH->value => self::PUBLISH->value,
            self::DEFAULT->value => self::DEFAULT->value,
        ];
    }

}