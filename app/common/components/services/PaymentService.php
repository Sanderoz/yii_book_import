<?php

namespace common\components\services;

use common\components\dto\BaseDTO;
use common\components\dto\payment\banks\requests\sbp\GetDTO;
use common\components\dto\payment\banks\requests\sbp\RegisterDTO;
use common\components\dto\payment\types\sbp\AbstractRegisterDTO;
use common\components\enums\PaymentType;
use common\components\exceptions\OrderException;
use common\components\interfaces\payment\OrderPaymentInterface;
use common\components\interfaces\payment\PaymentInterface;
use common\components\interfaces\payment\SbpPaymentInterface;
use common\models\OrderPayment;
use Yii;
use yii\httpclient\Exception;

class PaymentService
{
    public function __construct(OrderPaymentInterface $order)
    {
        $this->setOrder($order);
    }

    private OrderPaymentInterface $_order;

    /**
     * Создание заказа в банке
     * @throws \Exception
     */
    private function createPayment(PaymentType $paymentType): void
    {
        switch ($paymentType) {
            case(PaymentType::CARD):
                break;
            case(PaymentType::SBP):
                $payment = $this->getOrder()->getPaymentModel(PaymentType::SBP);
                if (!empty($payment->getSbpPaymentModel()))
                    return;

                $service = $this->getSbpBankPayment();
                $response = $service->getSbpRegister(new RegisterDTO(
                    $this->getOrder()->getOrderNumber(),
                    $this->getOrder()->getTotalPrice(),
                    $this->getSuccessUrl()
                ));

                // TODO: логирование
                if (!empty($response->getErrorMessage()))
                    throw new OrderException($response->getErrorMessage());

                $payment->setSbpPayment(
                    $service->getPaymentStatus($paymentType),
                    $response->getBank(),
                    '',
                    $response->getOrderId(),
                    $response->getFormUrl(),
                    '',
                    '',
                    $response->getErrorMessage(),
                    $response->getErrorCode()
                );
                break;
        }
    }

    /**
     * @param OrderPaymentInterface $order
     * @return array
     * @throws Exception
     * @throws OrderException
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function getSbpData(): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        $this->createPayment(PaymentType::SBP);
        $payment = $this->getOrder()->getPaymentModel();
        $result = [
            'form_url' => $payment->getSbpPaymentModel()->form_url,
            'error_message' => ''
        ];

        if (!empty($payment->getSbpPaymentModel()->payload))
            return array_merge(['payload' => $payment->getSbpPaymentModel()->payload], $result);

        $service = $this->getSbpBankPayment();
        $qrData = $service->getSbpGet(new GetDTO($payment->getSbpPaymentModel()->order_id));
        $payment->setSbpPayment(
            $service->getPaymentStatus(PaymentType::SBP, $qrData->getQrStatus()),
            $qrData->getBank(),
            $qrData->getQrId(),
            $payment->getSbpPaymentModel()->order_id,
            $payment->getSbpPaymentModel()->form_url,
            $qrData->getPayload(),
            $qrData->getQrStatus(),
            $qrData->getErrorMessage(),
            $qrData->getErrorCode()
        );

        $transaction->commit();

        $result['error_message'] = $qrData->getErrorMessage();
        return array_merge(['payload' => $qrData->getPayload()], $result);
    }

    private function setOrder(OrderPaymentInterface $order): void
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
    private function getSbpBankPayment(): SbpPaymentInterface&PaymentInterface
    {
        return Yii::$container->get(SbpPaymentInterface::class);
    }

    private function getCardBankPayment(): array
    {
        return [];
    }

    private function getSuccessUrl(): string
    {
        return '';
    }
}