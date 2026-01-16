<?php

namespace backend\controllers;

use common\models\Redirect;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контролер для базового керування редіректами.
 */
class RedirectController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Список редіректів + форма додавання.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Redirect();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Повідомляємо про успішне збереження.
            Yii::$app->session->setFlash('success', 'Редірект збережено.');
            return $this->redirect(['index']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Redirect::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Видалення редіректу.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Пошук моделі.
     *
     * @param int $id
     * @return Redirect
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Redirect::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Редірект не знайдено.');
    }
}
