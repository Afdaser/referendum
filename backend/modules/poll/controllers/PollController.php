<?php

namespace backend\modules\poll\controllers;

use common\models\Poll;
use common\models\PollStaticFaq;
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
        $faqFormModels = $this->ensureFaqPlaceholders([], null);

        if ($this->request->isPost) {
            $faqFormModels = $this->buildFaqModelsFromPost(null);
            if ($model->load($this->request->post()) && $model->save()) {
                $this->savePollFaq($model, $faqFormModels);
                // Після збереження синхронізуємо теги, щоб одразу створити зв'язки.
                $model->syncTags($model->tagNames);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'faqModels' => $faqFormModels,
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
        $faqFormModels = $this->ensureFaqPlaceholders($model->staticFaqs, $model->id);

        if ($this->request->isPost) {
            $faqFormModels = $this->buildFaqModelsFromPost($model->id);
            if ($model->load($this->request->post()) && $model->save()) {
                $this->savePollFaq($model, $faqFormModels);
                // Оновлюємо теги після збереження, щоб відобразити всі зміни зі сторінки редагування.
                $model->syncTags($model->tagNames);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'faqModels' => $faqFormModels,
        ]);
    }

    private function buildFaqModelsFromPost(?int $pollId): array
    {
        $faqPost = Yii::$app->request->post('PollStaticFaq', []);
        $faqModels = [];
        $position = 0;

        foreach ($faqPost as $faqRow) {
            $question = trim((string) ($faqRow['question'] ?? ''));
            $answer = trim((string) ($faqRow['answer'] ?? ''));
            $id = isset($faqRow['id']) ? (int) $faqRow['id'] : null;

            if ($question === '' && $answer === '') {
                continue;
            }

            $faqModel = $id ? PollStaticFaq::findOne(['id' => $id, 'poll_id' => $pollId]) : null;
            if (!$faqModel) {
                $faqModel = new PollStaticFaq();
            }

            $faqModel->poll_id = (int) $pollId;
            $faqModel->question = $question;
            $faqModel->answer = $answer;
            // Даємо змогу окремо керувати статичним/динамічним режимом FAQ.
            $faqModel->is_dynamic = (int) (($faqRow['is_dynamic'] ?? 0) ? 1 : 0);
            $faqModel->position = $position++;
            $faqModels[] = $faqModel;
        }

        return $this->ensureFaqPlaceholders($faqModels, $pollId);
    }

    private function savePollFaq(Poll $model, array $faqModels): void
    {
        $savedIds = [];
        foreach ($faqModels as $faqModel) {
            if (trim((string) $faqModel->question) === '' || trim((string) $faqModel->answer) === '') {
                continue;
            }
            $faqModel->poll_id = (int) $model->id;
            $faqModel->save(false);
            $savedIds[] = $faqModel->id;
        }

        if (!empty($savedIds)) {
            PollStaticFaq::deleteAll(['and', ['poll_id' => $model->id], ['not in', 'id', $savedIds]]);
        } else {
            PollStaticFaq::deleteAll(['poll_id' => $model->id]);
        }
    }

    private function ensureFaqPlaceholders(array $faqModels, ?int $pollId): array
    {
        $faqModels = array_values($faqModels);
        $faqModels[] = new PollStaticFaq(['poll_id' => (int) $pollId]);
        $faqModels[] = new PollStaticFaq(['poll_id' => (int) $pollId]);
        return $faqModels;
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
}
