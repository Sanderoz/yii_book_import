<?php

namespace common\models;

class BaseModel extends \yii\db\ActiveRecord
{
    /**
     * Return array of validation errors as a string
     * @return string
     */
    public function getValidateErrorsAsString(): string
    {
        $result = '';
        foreach ($this->getErrors() as $attribute => $error)
            $result .= $this->getAttributeLabel($attribute) . ': ' . implode(', ', $error) . '<br>';
        return $result;
    }
}