<?php

namespace common\components\dto;

use common\components\exceptions\DTOException;
use ReflectionClass;

abstract class BaseDTO
{
    /**
     * @throws \Exception
     */
    public function __construct(array $fields)
    {
        foreach ($fields as $name => $value)
            if (property_exists($this, $name))
                $this->$name = $value;

        /** Проверка на заполненность обязательных полей, установленных с помощью атрибута Required */
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $property)
            foreach ($property->getAttributes() as $attribute) {
                $property_name = $property->getName();
                if (str_contains($attribute->getName(), 'Required') and empty($this->$property_name))
                    throw new DTOException("Ответ не содержит обязательного поля '$property_name' или оно пусто");
            }
    }

    public function getData(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $name => $value)
            if ($value !== null)
                $result[$name] = $value;

        return $result;
    }
}