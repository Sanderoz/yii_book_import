<?php

namespace common\components\dto\payment\types\sbp;

use common\components\dto\BaseDTO;

abstract class AbstractGetDTO extends BaseDTO
{
    /**
     * Содержимое зарегистрированного в СБП QRкода.
     * @return string
     */
    public abstract function getPayload(): string;

    /**
     * Идентификатор QR-кода.
     * @return string
     */
    public abstract function getQrId(): string;

    /**
     * Состояние запроса QR_кода
     * @return string
     */
    public abstract function getQrStatus(): string;

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
     * Банк, проводивший операцию
     * @return string
     */
    public abstract function getBank(): string;
}