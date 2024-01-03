<?php

namespace common\components\dto\payment\banks\requests\sbp;


class StatusDTO
{
    /**
     * @property string $orderNumber
     * @property string $qrId
     */
    public function __construct(
        protected string $orderNumber,
        protected string $qrId
    )
    {
    }

    public function getAlfaData(): array
    {
        /**
         * Возможные поля для передачи в запросе
         * @property string $mdOrder Номер заказа в системе платёжного шлюза.
         * @property string $qrId Идентификатор QR-кода.
         */
        return [
            'mdOrder' => $this->orderNumber,
            'qrId' => $this->qrId
        ];
    }
}