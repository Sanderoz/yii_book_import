<?php

namespace backend\controllers;

use backend\models\LoginForm;
use common\models\Messages;
use common\models\Settings;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        $model = Settings::find()->all();
        if ($data = Yii::$app->request->post() and count($data) > 1 and Yii::$app->request->validateCsrfToken()) {
            foreach ($model as $setting) {
                /**
                 * @var Settings $setting
                 */
                if (isset($data[$setting->key]) and $setting->value != $data[$setting->key]) {
                    $setting->value = $data[$setting->key];
                    $setting->save();
                }

            }
        }
        return $this->render('settings', ['model' => $model]);
    }

    public function actionMessages()
    {
        return $this->render(
            'messages',
            [
                'dataProvider' => new ActiveDataProvider([
                    'query' => Messages::find(),
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ])
            ]
        );
    }

}
