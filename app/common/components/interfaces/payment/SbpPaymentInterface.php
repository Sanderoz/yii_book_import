<?php

namespace common\components\interfaces\payment;

use common\components\dto\payment\banks\requests\sbp\GetDTO;
use common\components\dto\payment\banks\requests\sbp\RegisterDTO;
use common\components\dto\payment\banks\requests\sbp\StatusDTO;
use common\components\dto\payment\types\sbp\AbstractGetDTO;
use common\components\dto\payment\types\sbp\AbstractRegisterDTO;
use common\components\dto\payment\types\sbp\AbstractStatusDTO;

interface SbpPaymentInterface
{
    /**
     * Регистрация заказа
     * @param RegisterDTO $data
     * @return AbstractRegisterDTO
     */
    public function getRegister(RegisterDTO $data): AbstractRegisterDTO;

    /**
     * Проверка статуса qr кода
     * @param StatusDTO $data
     * @return AbstractStatusDTO
     */
    public function getStatus(StatusDTO $data): AbstractStatusDTO;

    /**
     * Получение qr кода
     * @param GetDTO $data
     * @return AbstractGetDTO
     */
    public function getGet(GetDTO $data): AbstractGetDTO;
}