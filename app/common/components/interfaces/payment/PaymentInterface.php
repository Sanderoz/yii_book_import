<?php

namespace common\components\interfaces\payment;

use common\components\dto\payment\banks\requests\sbp\GetDTO;
use common\components\dto\payment\banks\requests\sbp\RegisterDTO;
use common\components\dto\payment\banks\requests\sbp\StatusDTO;
use common\components\dto\payment\types\sbp\AbstractGetDTO;
use common\components\dto\payment\types\sbp\AbstractRegisterDTO;
use common\components\dto\payment\types\sbp\AbstractStatusDTO;
use common\components\enums\PaymentStatus;
use common\components\enums\PaymentType;

interface PaymentInterface
{
    /**
     * Статус оплаты
     * @param PaymentType $type
     * @param string|null $status
     * @return PaymentStatus
     */
    public function getPaymentStatus(PaymentType $type, ?string $status): PaymentStatus;
}