<?php

namespace common\components\dto\payment\banks\responses\alfa\sbp;

use common\components\dto\payment\types\sbp\AbstractRegisterDTO;

class RegisterDTO extends AbstractRegisterDTO
{
    protected string $orderId = '';
    protected string $formUrl = '';
    protected string $ErrorMessage = '';
    protected string $ErrorCode = '';


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
        return $this->ErrorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->ErrorMessage;
    }

    public function getBank(): string
    {
        return 'alfa';
    }

}