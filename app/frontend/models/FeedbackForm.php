<?php

namespace frontend\models;

use common\models\Messages;
use common\models\Settings;
use common\models\User;
use Yii;

class FeedbackForm extends Messages
{
    public string $subject = 'Форма обратной связи';
    public $verifyCode;

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                ['verifyCode', 'captcha']
            ]
        );
    }

    public function attributeLabels(): array
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'verifyCode' => 'Код подтверждения',
            ]
        );
    }

    /**
     * @return bool
     */
    public function sendEmail(): bool
    {
        if ($email = Settings::findOne(['key' => 'email']))
            return Yii::$app->mailer->compose()
                ->setTo($email->value)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->message)
                ->send();

        return false;
    }
}
