<?php

namespace common\components\dto\payment\banks\responses\alfa\sbp;

use common\components\dto\payment\types\sbp\AbstractStatusDTO;

class StatusDTO extends AbstractStatusDTO
{
    protected string $errorCode = '';
    protected string $errorMessage = '';
    protected string $qrStatus = '';
    protected string $qrType = '';
    protected string $transactionState = '';

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getQrStatus(): string
    {
        return $this->qrStatus;
    }

    public function getBank(): string
    {
        return 'alfa';
    }

}