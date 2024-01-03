<?php

namespace common\components\interfaces\payment;


use common\components\enums\PaymentType;
use common\components\interfaces\RequestDTOInterface;

interface OrderPaymentInterface
{
    /**
     * Тип оплаты (по карте/сбп)
     * @return PaymentType
     */
    public function getPaymentType(): PaymentType;

    /**
     * Номер заказа
     * @return string
     */
    public function getOrderNumber(): string;

    /**
     * Id заказа
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Общая стоимость заказа
     * @return int
     */
    public function getTotalPrice(): int;

}