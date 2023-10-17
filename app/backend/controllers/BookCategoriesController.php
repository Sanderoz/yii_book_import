<?php

namespace backend\controllers;

use app\components\exceptions\FileException;
use common\components\traits\Tree;
use common\models\BookCategories;
use common\models\Files;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BookCategoryController implements the CRUD actions for BookCategory model.
 */
class BookCategoriesController extends Controller
{
    use Tree;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all BookCategory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $db_data = BookCategories::find()->asArray()->all();
        $data = empty($db_data) ? [] : $this->buildTree($db_data);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * @param int $id ID
     * @throws NotFoundHttpException
     * @throws FileException
     */
    public function actionView(int $id = null)
    {
        if (is_null($id))
            $model = new BookCategories();
        else
            $model = $this->findModel($id);

        $file = new Files();
        if ($model->load($this->request->post())) {
            $model->image = $file->upload() ?? $model->image;
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', 'Запись успешно сохранена');
                return $this->redirect(['/book-categories']);
            } else {
                \Yii::$app->session->setFlash('error', $model->getValidateErrorsAsString());
            }
        }

        return $this->render('view', [
            'model' => $model,
            'file' => $file
        ]);
    }


    /**
     * Updates an existing BookCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BookCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BookCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return BookCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = BookCategories::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
