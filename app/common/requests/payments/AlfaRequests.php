<?php

namespace common\requests\payments;

use common\components\dto\payment\banks\responses\alfa\sbp\GetDTO;
use common\components\dto\payment\banks\responses\alfa\sbp\RegisterDTO;
use common\components\dto\payment\banks\responses\alfa\sbp\StatusDTO;
use common\components\dto\payment\types\sbp\AbstractGetDTO;
use common\components\dto\payment\types\sbp\AbstractRegisterDTO;
use common\components\dto\payment\types\sbp\AbstractStatusDTO;
use common\components\exceptions\SettingsException;
use common\components\interfaces\payment\SbpPaymentInterface;
use common\components\dto\payment\banks\requests\sbp\RegisterDTO as RequestRegisterDTO;
use common\components\dto\payment\banks\requests\sbp\StatusDTO as RequestStatusDTO;
use common\components\dto\payment\banks\requests\sbp\GetDTO as RequestGetDTO;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class AlfaRequests implements SbpPaymentInterface
{
    private string $userName;
    private string $password;

    /**
     * @throws SettingsException
     */
    public function __construct(
        private readonly Client $client = new Client()
    )
    {
        if (!Yii::$app->helper->isDev()) {
            if (empty($_ENV['ALFA_PASSWORD']) or empty($_ENV['ALFA_USERNAME']))
                throw new SettingsException('Отсутсвуют настройки доступа');

            $this->userName = $_ENV['ALFA_USERNAME'];
            $this->password = $_ENV['ALFA_PASSWORD'];
            return;
        }

        if (empty($_ENV['ALFA_PASSWORD_DEV']) or empty($_ENV['ALFA_USERNAME_DEV']))
            throw new SettingsException('Отсутсвуют настройки доступа');

        $this->userName = $_ENV['ALFA_USERNAME_DEV'];
        $this->password = $_ENV['ALFA_PASSWORD_DEV'];
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    protected function getAuthData(): array
    {
        return [
            'password' => $this->password,
            'userName' => $this->userName
        ];
    }

    // document url: https://pay.alfabank.ru/ecommerce/instructions/SBP_C2B.pdf

    /**
     * @param RequestRegisterDTO $data
     * @return AbstractRegisterDTO
     * @throws Exception
     * @throws \Exception
     */
    public function getRegister(RequestRegisterDTO $data): AbstractRegisterDTO
    {
        $response = $this->getClient()->post(
            Url::toRoute($this->getRegisterLink(), array_merge($this->getAuthData(), $data->getAlfaData()))
        )->send();

        return new RegisterDTO((array)$response);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getStatus(RequestStatusDTO $data): AbstractStatusDTO
    {
        $response = $this->getClient()->post(
            Url::toRoute($this->getRegisterLink(), array_merge($this->getAuthData(), $data->getAlfaData())),
            [],
            [
                'Сontent-type' => 'application/x-www-form-urlencoded'
            ]
        )->send();

        return new StatusDTO((array)$response);
    }


    /**
     * @param RequestGetDTO $data
     * @return AbstractGetDTO
     * @throws Exception
     * @throws \Exception
     */
    public function getGet(RequestGetDTO $data): AbstractGetDTO
    {
        $response = $this->getClient()->post(
            Url::toRoute($this->getGetLink(), array_merge($this->getAuthData(), $data->getAlfaData())),
            [],
            [
                'Сontent-type' => 'application/x-www-form-urlencoded'
            ]
        )->send();

        return new GetDTO((array)$response);
    }

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
}