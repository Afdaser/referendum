<?php

namespace backend\modules\poll\controllers;

use Yii;
use common\models\TagStaticText;
use common\models\search\TagStaticTextSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контролер для керування статичними текстами тегів у бекенді.
 */
class TagStaticTextController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Відображає перелік усіх статичних текстів.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TagStaticTextSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показує деталі окремого статичного тексту.
     *
     * @param int $id ID запису
     * @return string
     * @throws NotFoundHttpException якщо запис не знайдено
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'placeholders' => TagStaticText::placeholderDescriptions(),
        ]);
    }

    /**
     * Створює новий статичний текст.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TagStaticText();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Текст збережено.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'placeholders' => TagStaticText::placeholderDescriptions(),
        ]);
    }

    /**
     * Оновлює наявний статичний текст.
     *
     * @param int $id ID запису
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException якщо запис не знайдено
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Текст збережено.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'placeholders' => TagStaticText::placeholderDescriptions(),
        ]);
    }

    /**
     * Видаляє статичний текст.
     *
     * @param int $id ID запису
     * @return \yii\web\Response
     * @throws NotFoundHttpException якщо запис не знайдено
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Текст видалено.');

        return $this->redirect(['index']);
    }

    /**
     * Шукає модель статичного тексту за первинним ключем.
     *
     * @param int $id ID запису
     * @return TagStaticText
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = TagStaticText::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Сторінку не знайдено.');
    }
}
