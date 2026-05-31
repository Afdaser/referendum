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
        // Не нашкодити: не маскуємо невалідні page=0/000 як першу сторінку, а повертаємо 404.
        $rawPage = (int) $page;
        if ($rawPage < 1) {
            throw new NotFoundHttpException(Yii::t('poll', 'Сторінку новин не знайдено.'));
        }
        $page = $rawPage;

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
        // Не нашкодити: Pagination::setPage() не повертає bool, тому валідацію діапазону робимо явно.
        $requestedPageZeroBased = $page - 1;
        $pageCount = (int) $pagination->getPageCount();
        // Для порожнього списку допускаємо лише першу сторінку; для непорожнього — 0..(pageCount-1).
        $maxAllowedPageZeroBased = $pageCount > 0 ? $pageCount - 1 : 0;
        if ($requestedPageZeroBased < 0 || $requestedPageZeroBased > $maxAllowedPageZeroBased) {
            throw new NotFoundHttpException(Yii::t('poll', 'Сторінку новин не знайдено.'));
        }
        $pagination->setPage($requestedPageZeroBased, true);

        $items = (clone $baseQuery)
            // Сортуємо від найновіших до найстаріших.
            ->orderBy(['published_at' => SORT_DESC, 'id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $currentPage = $pagination->page + 1;
        $this->registerPaginationMeta($pagination, $currentPage);

        $newsPageTitle = Yii::t('poll', 'Новини | Referendum');
        $newsPageDescription = Yii::t('poll', 'Останні новини Referendum: актуальні публікації, огляди та оновлення.');
        Yii::$app->page->setTitle($newsPageTitle);
        Yii::$app->page->setDescription($newsPageDescription);
        $this->view->title = $newsPageTitle;

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
            throw new \yii\web\NotFoundHttpException(Yii::t('poll', 'Новину не знайдено.'));
        }

        $canonicalUrl = Url::to(['/news/view', 'slug' => $model->slug], true);
        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);

        $pageTitle = Yii::t('poll', '{title} | Новини Referendum', [
            'title' => trim((string) $model->title),
        ]);
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
