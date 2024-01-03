<?php

namespace common\components\services;

use common\components\dto\BaseDTO;
use common\components\dto\payment\banks\requests\sbp\RegisterDTO;
use common\components\dto\payment\types\sbp\AbstractRegisterDTO;
use common\components\enums\PaymentType;
use common\components\interfaces\payment\OrderPaymentInterface;
use common\components\interfaces\payment\SbpPaymentInterface;
use Yii;

class PaymentService
{
    private OrderPaymentInterface $_order;

    /**
     * @throws \Exception
     */
    public function createPayment(OrderPaymentInterface $order): array
    {
        $this->setOrder($order);
        switch ($this->getOrder()->getPaymentType()) {
            case(PaymentType::CARD):
                break;
            case(PaymentType::SBP):
                $result = $this->getSbpPayment()->getRegister(new RegisterDTO(
                    $this->getOrder()->getOrderNumber(),
                    $this->getOrder()->getTotalPrice(),
                    $this->getSuccessUrl()
                ));
                break;
        }

        return [];
    }

    private function setOrder(OrderPaymentInterface $order)
    {
        $this->_order = $order;
    }

    private function getOrder(): OrderPaymentInterface
    {
        return $this->_order;
    }

    /**
     * @throws \Exception
     */
    private function getSbpPayment(): SbpPaymentInterface
    {
        return Yii::$container->get(SbpPaymentInterface::class);
    }

    private function getCardPayment()
    {
        return [];
    }

    private function getSuccessUrl()
    {
        return '';
    }
}