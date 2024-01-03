<?php

namespace common\components\dto\payment\banks\responses\alfa\sbp;

use common\components\dto\payment\types\sbp\AbstractGetDTO;

class GetDTO extends AbstractGetDTO
{
    protected string $payload = '';
    protected string $qrId = '';
    protected string $qrStatus = '';
    protected string $errorCode = '';
    protected string $errorMessage = '';

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function getQrId(): string
    {
        return $this->qrId;
    }

    public function getQrStatus(): string
    {
        return $this->qrStatus;
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