<?php

namespace backend\modules\poll\controllers;

use common\models\PollFaq;
use common\models\Poll;
use common\models\search\PollSearch;
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
            if ($model->load($this->request->post()) && $model->save()) {
                // Після збереження синхронізуємо теги, щоб одразу створити зв'язки.
                $model->syncTags($model->tagNames);
                $this->savePollFaq($model);
                return $this->redirect(['view', 'id' => $model->id]);
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Оновлюємо теги після збереження, щоб відобразити всі зміни зі сторінки редагування.
            $model->syncTags($model->tagNames);
            $this->savePollFaq($model);
            return $this->redirect(['view', 'id' => $model->id]);
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
     * Зберігає індивідуальні FAQ для конкретного опитування.
     */
    private function savePollFaq(Poll $poll): void
    {
        $rows = $this->request->post('PollFaq', []);
        $position = 0;
        $savedIds = [];

        foreach ($rows as $row) {
            $question = trim((string)($row['question'] ?? ''));
            $answer = trim((string)($row['answer'] ?? ''));
            $id = isset($row['id']) ? (int)$row['id'] : null;

            if ($question === '' && $answer === '') {
                continue;
            }

            $faq = $id ? PollFaq::findOne(['id' => $id, 'poll_id' => $poll->id]) : null;
            if (!$faq) {
                $faq = new PollFaq(['poll_id' => $poll->id]);
            }

            $faq->poll_id = $poll->id;
            $faq->question = $question;
            $faq->answer = $answer;
            $faq->position = $position++;
            $faq->save(false);
            $savedIds[] = $faq->id;
        }

        if (!empty($savedIds)) {
            PollFaq::deleteAll(['and', ['poll_id' => $poll->id], ['not in', 'id', $savedIds]]);
        } else {
            PollFaq::deleteAll(['poll_id' => $poll->id]);
        }
    }
}
