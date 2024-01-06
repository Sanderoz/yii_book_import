<?php

namespace common\requests\payments;

use common\components\dto\payment\banks\responses\alfa\sbp\GetDTO;
use common\components\dto\payment\banks\responses\alfa\sbp\RegisterDTO;
use common\components\dto\payment\banks\responses\alfa\sbp\StatusDTO;
use common\components\dto\payment\types\sbp\AbstractGetDTO;
use common\components\dto\payment\types\sbp\AbstractRegisterDTO;
use common\components\dto\payment\types\sbp\AbstractStatusDTO;
use common\components\enums\PaymentStatus;
use common\components\enums\PaymentType;
use common\components\exceptions\RequestException;
use common\components\exceptions\SettingsException;
use common\components\interfaces\payment\PaymentInterface;
use common\components\interfaces\payment\SbpPaymentInterface;
use common\components\dto\payment\banks\requests\sbp\RegisterDTO as RequestRegisterDTO;
use common\components\dto\payment\banks\requests\sbp\StatusDTO as RequestStatusDTO;
use common\components\dto\payment\banks\requests\sbp\GetDTO as RequestGetDTO;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class AlfaRequests implements SbpPaymentInterface, PaymentInterface
{
    /** QR-код cформирован;  */
    const SBP_STATUS_STARTED = 'STARTED';

    /** Заказ принят к оплате; */
    const SBP_STATUS_CONFIRMED = 'CONFIRMED';

    /** оплата отклонена;  */
    const SBP_STATUS_REJECTED = 'REJECTED';

    /** оплате по QR-коду отклонена мерчантом; */
    const SBP_STATUS_REJECTED_BY_USER = 'REJECTED_BY_USER';

    /** заказ оплачен. */
    const SBP_STATUS_ACCEPTED = 'ACCEPTED';

    // Типы оплат
    const PAYMENT_TYPE_SBP = 'SBP';
    const PAYMENT_TYPE_CARD = 'CARD';

    // Статусы ответов
    const STATUS_SUCCESS = 200;

    // Доступы
    private string $_userName;
    private string $_password;


    /**
     * @throws SettingsException
     */
    public function __construct(
        private readonly Client $_client = new Client()
    )
    {
        if (!Yii::$app->helper->isDev()) {
            if (empty($_ENV['ALFA_PASSWORD']) or empty($_ENV['ALFA_USERNAME']))
                throw new SettingsException('Отсутсвуют настройки доступа');

            $this->_userName = $_ENV['ALFA_USERNAME'];
            $this->_password = $_ENV['ALFA_PASSWORD'];
            return;
        }

        if (empty($_ENV['ALFA_PASSWORD_DEV']) or empty($_ENV['ALFA_USERNAME_DEV']))
            throw new SettingsException('Отсутсвуют настройки доступа');

        $this->_userName = $_ENV['ALFA_USERNAME_DEV'];
        $this->_password = $_ENV['ALFA_PASSWORD_DEV'];
    }

    protected function getClient(): Client
    {
        return $this->_client;
    }

    protected function getAuthData(): array
    {
        return [
            'password' => $this->_password,
            'userName' => $this->_userName
        ];
    }

    // document url: https://pay.alfabank.ru/ecommerce/instructions/SBP_C2B.pdf

    /**
     * @param RequestRegisterDTO $data
     * @return AbstractRegisterDTO
     * @throws Exception
     * @throws \Exception
     */
    public function getSbpRegister(RequestRegisterDTO $data): AbstractRegisterDTO
    {
        $response = $this->getClient()->post(
            ltrim(Url::to(array_merge([$this->getRegisterLink()], $this->getAuthData(), $data->getAlfaData()), true), '/')
        )->send();

        if ($response->getStatusCode() != self::STATUS_SUCCESS)
            throw new RequestException();

        return new RegisterDTO($response->getData());
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getSbpStatus(RequestStatusDTO $data): AbstractStatusDTO
    {
        $response = $this->getClient()->post(
            ltrim(Url::to(array_merge([$this->getStatusLink()], $this->getAuthData(), $data->getAlfaData()), true), '/'),
            [],
            [
                'Сontent-type' => 'application/x-www-form-urlencoded'
            ]
        )->send();

        if ($response->getStatusCode() != self::STATUS_SUCCESS)
            throw new RequestException();

        return new StatusDTO($response->getData());
    }


    /**
     * @param RequestGetDTO $data
     * @return AbstractGetDTO
     * @throws Exception
     * @throws \Exception
     */
    public function getSbpGet(RequestGetDTO $data): AbstractGetDTO
    {
        $response = $this->getClient()->post(
            ltrim(Url::to(array_merge([$this->getGetLink()], $this->getAuthData(), $data->getAlfaData()), true), '/'),
            [],
            [
                'Сontent-type' => 'application/x-www-form-urlencoded'
            ]
        )->send();

        if ($response->getStatusCode() != self::STATUS_SUCCESS)
            throw new RequestException();

        return new GetDTO($response->getData());
    }

    public function getPaymentStatus(PaymentType $type, ?string $status): PaymentStatus
    {
        return match ($type) {
            PaymentType::SBP => function () use ($status) {
                return match ($status) {
                    self::SBP_STATUS_ACCEPTED => PaymentStatus::PAID,
                    self::SBP_STATUS_CONFIRMED => PaymentStatus::PROCESSED,
                    self::SBP_STATUS_REJECTED_BY_USER, self::SBP_STATUS_REJECTED => PaymentStatus::CANCELED,
                    default => PaymentStatus::NEW
                };
            },
            default => PaymentStatus::NEW
        };
    }

    /************** Links ************/

    /**
     * @return string
     */
    private function getRegisterLink(): string
    {
        if (!Yii::$app->helper->isDev())
            return 'https://pay.alfabank.ru/payment/rest/register.do';

        return 'https://alfa.rbsuat.com/payment/rest/register.do';
    }

    private function getGetLink(): string
    {
        if (!Yii::$app->helper->isDev())
            return 'https://pay.alfabank.ru/payment/rest/sbp/c2b/qr/dynamic/get.do';

        return 'https://alfa.rbsuat.com/payment/rest/sbp/c2b/qr/dynamic/get.do';
    }

    private function getStatusLink(): string
    {
        if (!Yii::$app->helper->isDev())
            return 'https://pay.alfabank.ru/payment/rest/sbp/c2b/qr/dynamic/status.do';

        return 'https://alfa.rbsuat.com/payment/rest/sbp/c2b/qr/dynamic/status.do';
    }
    /************** END Links ************/

}