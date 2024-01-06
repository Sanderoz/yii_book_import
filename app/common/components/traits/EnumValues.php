<?php

namespace common\components\traits;

use ReflectionEnum;

trait EnumValues
{
    /**
     * Все возможные значения enum для rules
     * @return array
     */
    public static function getValues(): array
    {
        $reflection = new ReflectionEnum(self::class);
        $casesValues = [];
        foreach ($reflection->getCases() as $item)
            $casesValues[] = $item->getValue()->value;

        return $casesValues;
    }
}