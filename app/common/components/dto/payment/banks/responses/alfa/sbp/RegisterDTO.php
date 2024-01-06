<?php

namespace common\components\dto\payment\banks\responses\alfa\sbp;

use common\components\dto\payment\types\sbp\AbstractRegisterDTO;

class RegisterDTO extends AbstractRegisterDTO
{
    protected string $orderId = '';
    protected string $formUrl = '';
    protected string $errorMessage = '';
    protected string $errorCode = '';


    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getFormUrl(): string
    {
        return $this->formUrl;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getBank(): string
    {
        return 'alfa';
    }

}