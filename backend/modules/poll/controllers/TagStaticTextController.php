<?php

namespace backend\modules\poll\controllers;

use Yii;
use common\models\TagStaticText;
use common\models\search\TagStaticTextSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TagStaticTextController implements the CRUD actions for TagStaticText model.
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
     * Lists all TagStaticText models.
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
     * Displays a single TagStaticText model.
     *
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'placeholders' => TagStaticText::placeholderDescriptions(),
        ]);
    }

    /**
     * Creates a new TagStaticText model.
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
     * Updates an existing TagStaticText model.
     *
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
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
     * Deletes an existing TagStaticText model.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Текст видалено.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the TagStaticText model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
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
