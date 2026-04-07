<?php

namespace backend\controllers;

use common\models\CookieConsentSetting;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Керування налаштуваннями cookie consent через адмінку.
 */
class CookieConsentSettingController extends Controller
{
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
     * Єдина сторінка: форма створення + список існуючих налаштувань.
     */
    public function actionIndex()
    {
        $model = new CookieConsentSetting();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Налаштування cookie consent збережено.');
            return $this->redirect(['index']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => CookieConsentSetting::find()->orderBy(['host' => SORT_ASC, 'language_code' => SORT_ASC, 'id' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'languageOptions' => CookieConsentSetting::getLanguageOptions(),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel((int) $id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Налаштування cookie consent оновлено.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'languageOptions' => CookieConsentSetting::getLanguageOptions(),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel((int) $id)->delete();
        Yii::$app->session->setFlash('success', 'Налаштування cookie consent видалено.');
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): CookieConsentSetting
    {
        if (($model = CookieConsentSetting::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Налаштування cookie consent не знайдено.');
    }
}

