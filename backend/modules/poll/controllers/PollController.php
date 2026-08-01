<?php

namespace backend\modules\poll\controllers;

use common\models\PollFaq;
use common\models\Poll;
use common\models\User;
use common\models\search\PollSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * PollController implements the CRUD actions for Poll model.
 */
class PollController extends Controller
{
    /** Мінімальна кількість символів у запиті пошуку користувачів. */
    public const USER_SEARCH_MIN_LENGTH = 2;

    /** Максимальна кількість користувачів на одній сторінці результатів. */
    public const USER_SEARCH_PAGE_SIZE = 20;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['user-search'],
                    // Пошук доступний лише авторизованому адміністратору backend.
                    'rules' => [[
                        'allow' => true,
                        'roles' => ['@'],
                    ]],
                ],
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
     * Повертає сторінку користувачів для AJAX-поля Select2.
     *
     * @return array
     */
    public function actionUserSearch(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $term = trim((string)$this->request->get('q', ''));
        $page = max(1, (int)$this->request->get('page', 1));

        if (mb_strlen($term) < self::USER_SEARCH_MIN_LENGTH) {
            return ['results' => [], 'pagination' => ['more' => false]];
        }

        $query = User::find()->select(['id', 'username']);
        if (ctype_digit($term)) {
            $query->andWhere(['or', ['id' => (int)$term], ['like', 'username', $term]]);
        } else {
            $query->andWhere(['like', 'username', $term]);
        }

        // Навмисно беремо лише одну обмежену сторінку, а не повний список користувачів, задля швидкодії.
        $users = $query
            ->orderBy(['username' => SORT_ASC, 'id' => SORT_ASC])
            ->offset(($page - 1) * self::USER_SEARCH_PAGE_SIZE)
            ->limit(self::USER_SEARCH_PAGE_SIZE + 1)
            ->asArray()
            ->all();
        $hasMore = count($users) > self::USER_SEARCH_PAGE_SIZE;
        $users = array_slice($users, 0, self::USER_SEARCH_PAGE_SIZE);

        return [
            'results' => array_map(static function (array $user): array {
                return ['id' => (string)$user['id'], 'text' => $user['username']];
            }, $users),
            'pagination' => ['more' => $hasMore],
        ];
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

            // Не зберігаємо неповні FAQ-рядки, щоб не створювати «невидимі» записи.
            if ($question === '' || $answer === '') {
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
            // Додатково валідуємо модель перед збереженням навіть для повних рядків.
            if (!$faq->validate()) {
                continue;
            }

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
