<?php

namespace backend\modules\poll\controllers;

use common\models\Poll;
use common\models\search\PollSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PollController implements the CRUD actions for Poll model.
 */
class PollController extends Controller
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
     * Lists all Poll models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PollSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Гарантуємо, що в адмінці нові опитування показуються першими за замовчуванням.
        $dataProvider->getSort()->defaultOrder = ['date_add' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Poll model.
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
     * Creates a new Poll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Poll();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    // Після збереження синхронізуємо теги, щоб одразу створити зв'язки.
                    $model->syncTags($model->tagNames);
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                // Показуємо повідомлення про помилку, щоб не залишати користувача без пояснень.
                $this->addSaveErrorFlash($model);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Poll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                // Оновлюємо теги після збереження, щоб відобразити всі зміни зі сторінки редагування.
                $model->syncTags($model->tagNames);
                return $this->redirect(['view', 'id' => $model->id]);
            }

            // Показуємо повідомлення про помилку, щоб не залишати користувача без пояснень.
            $this->addSaveErrorFlash($model);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Poll model.
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
     * Finds the Poll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Poll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Poll::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Додає повідомлення про помилки збереження опитування в адмінці.
     *
     * @param Poll $model Поточна модель опитування.
     */
    private function addSaveErrorFlash(Poll $model): void
    {
        // Якщо є помилки валідації, показуємо першу з них.
        if ($model->hasErrors()) {
            $errors = $model->getFirstErrors();
            $message = Yii::t('app', 'Не вдалося зберегти опитування: {error}', [
                'error' => reset($errors),
            ]);
        } else {
            // Якщо модель не має помилок, повідомляємо про внутрішню помилку сервера.
            $message = Yii::t('app', 'Внутрішня помилка сервера під час збереження опитування. Спробуйте ще раз.');
        }

        Yii::$app->session->setFlash('error', $message);
    }
}
