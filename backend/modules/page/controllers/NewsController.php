<?php

namespace backend\modules\page\controllers;

use common\models\News;
use common\models\search\NewsSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class NewsController extends Controller
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex(): string
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new News();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Спершу валідовуємо разом із файлом, потім зберігаємо файл і фінально запис у БД.
            if ($model->validate() && $model->uploadImage() && $model->save(false)) {
                return $this->redirect(['index']);
            }
        }

        if (!$this->request->isPost) {
            // Для нової форми підтягуємо значення за замовчуванням (чернетка), щоб не опублікувати новину випадково.
            $model->loadDefaultValues();
            // Не нашкодити: якщо схема ще не віддала default, явно ставимо український піддомен як історичний базовий варіант.
            $model->language_id = $model->language_id ?: 1;
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel((int)$id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Не видаляємо старе зображення автоматично: якщо файл не вибрано, поточний URL лишається без змін.
            if ($model->validate() && $model->uploadImage() && $model->save(false)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel((int)$id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): News
    {
        $model = News::findOne(['id' => $id]);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запис не знайдено.');
    }
}
