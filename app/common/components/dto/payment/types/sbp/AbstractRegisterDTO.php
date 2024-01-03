<?php

namespace common\components\dto\payment\types\sbp;

use common\components\dto\BaseDTO;

abstract class AbstractRegisterDTO extends BaseDTO
{
    /**
     * Номер заказа в платежной системе. Уникален в пределах системы.
     * @return string
     */
    public abstract function getOrderId(): string;

    /**
     * URL платежной формы, на который надо перенаправить браузер клиента
     * @return string
     */
    public abstract function getFormUrl(): string;

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