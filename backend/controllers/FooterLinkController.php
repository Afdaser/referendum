<?php

namespace backend\controllers;

use common\models\FooterLink;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контролер керування посиланнями футера.
 */
class FooterLinkController extends Controller
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
     * Список посилань футера + форма створення.
     */
    public function actionIndex()
    {
        $model = new FooterLink();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Підтверджуємо успішне додавання посилання.
            Yii::$app->session->setFlash('success', 'Посилання футера збережено.');
            return $this->redirect(['index']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => FooterLink::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Окрема сторінка редагування посилання.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel((int) $id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Посилання футера оновлено.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Видалення посилання футера.
     */
    public function actionDelete($id)
    {
        $this->findModel((int) $id)->delete();
        Yii::$app->session->setFlash('success', 'Посилання футера видалено.');

        return $this->redirect(['index']);
    }

    /**
     * Пошук моделі.
     */
    protected function findModel(int $id): FooterLink
    {
        if (($model = FooterLink::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Посилання футера не знайдено.');
    }
}
