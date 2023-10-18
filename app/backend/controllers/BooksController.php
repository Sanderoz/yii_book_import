<?php

namespace backend\controllers;

use app\components\exceptions\FileException;
use common\models\Authors;
use common\models\BookAuthors;
use common\models\BookBelongsCategories;
use common\models\BookCategories;
use common\models\Books;
use common\models\BooksQuery;
use common\models\BooksSearch;
use common\models\Files;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BooksController extends Controller
{
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
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param string $isbn Isbn
     * @throws NotFoundHttpException if the model cannot be found
     * @throws FileException
     * @throws Exception
     */
    public function actionView($id = null): \yii\web\Response|string
    {
        if (is_null($id))
            $model = new Books();
        else
            $model = $this->findModel($id);

        $file = new Files();

        if ($model->load(Yii::$app->request->post())) {
            $model->image = $file->upload() ?? $model->image;
            BookAuthors::updateAuthorRelations($model->isbn, Yii::$app->request->post('Authors', []));
            $transaction = Yii::$app->db->beginTransaction();
            if (
                $model->validate() and
                BookAuthors::updateAuthorRelations($model->isbn, Yii::$app->request->post('Authors', [])) and
                BookBelongsCategories::updateCategoriesRelations($model->isbn, Yii::$app->request->post('BookCategories', [])) and
                $model->save()
            ) {
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'Запись успешно сохранена');
                return $this->redirect(['/books']);
            } else {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('error', $model->getValidateErrorsAsString());
            }
        }

        return $this->render('view', [
            'model' => $model,
            'file' => $file
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $isbn Isbn
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $isbn Isbn
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne(['isbn' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
