<?php

namespace common\components\dto\payment\types\sbp;

use common\components\dto\BaseDTO;

abstract class AbstractStatusDTO extends BaseDTO
{
    /**
     * Код ошибки.
     * @return string
     */
    public abstract function getErrorCode(): string;

    /**
     * Описание ошибки
     * @return string
     */
    public abstract function getErrorMessage(): string;

    /**
     * Состояние запроса QR_кода
     * @return string
     */
    public abstract function getQrStatus(): string;

    /**
     * Банк, проводивший операцию
     * @return string
     */
    public abstract function getBank(): string;
}