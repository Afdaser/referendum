<?php

namespace backend\modules\poll\controllers;

use common\models\PollOption;
use common\models\search\PollOptionSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PollOptionController implements the CRUD actions for PollOption model.
 */
class PollOptionController extends Controller
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
     * Lists all PollOption models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PollOptionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PollOption model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PollOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($poll_id = null)
    {
        $model = new PollOption();

        // Визначаємо опитування з query-параметра і фіксуємо його для нової відповіді.
        $model->poll_id = $poll_id !== null
            ? (int) $poll_id
            : (int) Yii::$app->request->get('poll_id');

        if (!$model->poll_id) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Poll is required.'));
            return $this->redirect(['index']);
        }

        // Для адмінки автоматично підставляємо службові поля.
        if (!Yii::$app->request->isPost) {
            $model->user_id = (int) Yii::$app->user->id;
            $model->status = PollOption::OPTION_STATUS_PUBLISHED;
            $model->rating = 0;
            $model->loadDefaultValues();
        }

        if ($this->request->isPost) {
            $model->user_id = (int) Yii::$app->user->id;
            $model->status = PollOption::OPTION_STATUS_PUBLISHED;
            $model->rating = 0;
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/poll/poll/view', 'id' => $model->poll_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PollOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
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
     * Deletes an existing PollOption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PollOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PollOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PollOption::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
