<?php

namespace common\components\interfaces\payment;


use common\components\enums\PaymentStatus;
use common\components\exceptions\OrderException;
use common\models\OrderPaymentSbp;
use yii\db\Exception;

interface OrderPaymentDataInterface
{
    /**
     * Получение модели оплаты
     * @return OrderPaymentSbp|null
     */
    public function getSbpPaymentModel(): ?OrderPaymentSbp;

    /**
     * Создание/обновление оплаты
     * @param PaymentStatus $status Статус текущего банка в нормлаьный
     * @param string $bank
     * @param string|null $qr_id
     * @param string|null $order_id
     * @param string|null $form_url
     * @param string|null $payload
     * @param string|null $qr_status
     * @param string|null $error_message
     * @param string|null $error_code
     * @return void
     * @throws OrderException
     * @throws Exception
     */
    public function setSbpPayment(
        PaymentStatus $status,
        string        $bank,
        ?string       $qr_id = null,
        ?string       $order_id = null,
        ?string       $form_url = null,
        ?string       $payload = null,
        ?string       $qr_status = null,
        ?string       $error_message = null,
        ?string       $error_code = null
    ): void;

}