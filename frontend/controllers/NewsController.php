<?php

namespace frontend\controllers;

use common\models\News;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    public function actionIndex(int $page = 1)
    {
        $page = max(1, (int) $page);

        // Не нашкодити: показуємо тільки опубліковані новини, дата яких вже настала.
        $baseQuery = News::find()
            ->where(['is_published' => 1])
            ->andWhere(['<=', 'published_at', date('Y-m-d H:i:s')]);

        $totalCount = (int) (clone $baseQuery)->count();
        $pagination = new Pagination([
            'totalCount' => $totalCount,
            'pageSize' => 10,
            'route' => 'news/index',
            'pageParam' => 'page',
            'forcePageParam' => false,
            'validatePage' => true,
        ]);
        // Не нашкодити: явно валідуємо номер сторінки й повертаємо 404 для page поза діапазоном.
        if (!$pagination->setPage($page - 1, true)) {
            throw new NotFoundHttpException('Сторінку новин не знайдено.');
        }

        $items = (clone $baseQuery)
            // Сортуємо від найновіших до найстаріших.
            ->orderBy(['published_at' => SORT_DESC, 'id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $currentPage = $pagination->page + 1;
        $this->registerPaginationMeta($pagination, $currentPage);

        Yii::$app->page->setTitle('Новини | Referendum');
        Yii::$app->page->setDescription('Останні новини Referendum: актуальні публікації, огляди та оновлення.');
        $this->view->title = 'Новини | Referendum';

        return $this->render('index', [
            'items' => $items,
            'pagination' => $pagination,
        ]);
    }

    public function actionView(string $slug)
    {
        // Не нашкодити: відкриваємо лише публічні матеріали, доступні за датою.
        $model = News::find()
            ->where(['slug' => $slug, 'is_published' => 1])
            ->andWhere(['<=', 'published_at', date('Y-m-d H:i:s')])
            ->one();

        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('Новину не знайдено.');
        }

        $canonicalUrl = Url::to(['/news/view', 'slug' => $model->slug], true);
        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);

        $pageTitle = trim((string) $model->title) . ' | Новини Referendum';
        Yii::$app->page->setTitle($pageTitle);
        Yii::$app->page->setDescription(trim((string) ($model->desc ?: $model->excerpt)));
        $this->view->title = $pageTitle;

        return $this->render('view', ['model' => $model]);
    }

    private function registerPaginationMeta(Pagination $pagination, int $currentPage): void
    {
        $canonicalUrl = Url::to(['/news/index', 'page' => $currentPage > 1 ? $currentPage : null], true);
        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);

        if ($currentPage > 1) {
            $prevUrl = Url::to(['/news/index', 'page' => $currentPage - 1 > 1 ? $currentPage - 1 : null], true);
            Yii::$app->view->registerLinkTag([
                'rel' => 'prev',
                'href' => $prevUrl,
            ]);
        }

        $lastPage = (int) $pagination->getPageCount();
        if ($currentPage < $lastPage) {
            $nextUrl = Url::to(['/news/index', 'page' => $currentPage + 1], true);
            Yii::$app->view->registerLinkTag([
                'rel' => 'next',
                'href' => $nextUrl,
            ]);
        }
    }
}
