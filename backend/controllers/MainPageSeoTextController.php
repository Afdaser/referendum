<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\models\MainPageSeoText;

/**
 * Керує SEO-текстами для головної сторінки по доменах/піддоменах.
 */
class MainPageSeoTextController extends Controller
{
    /**
     * Перелік доменів із можливістю перейти до редагування.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $domains = $this->buildDomainOptions();
        $records = MainPageSeoText::find()->indexBy('domain')->all();

        return $this->render('index', [
            'domains' => $domains,
            'records' => $records,
        ]);
    }

    /**
     * Редагує SEO-текст для конкретного домену.
     *
     * @param string $domain
     * @return string|Response
     */
    public function actionUpdate(string $domain)
    {
        $domains = $this->buildDomainOptions();
        $domain = $this->normalizeDomain($domain);

        if (!isset($domains[$domain])) {
            throw new NotFoundHttpException('Домен не знайдено.');
        }

        $model = MainPageSeoText::findOne(['domain' => $domain]);
        if ($model === null) {
            $model = new MainPageSeoText([
                'domain' => $domain,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Нормалізуємо значення, щоб зайві пробіли не зберігались у БД.
            $model->heading = trim((string) $model->heading);
            $model->content = trim((string) $model->content);
            // Підчищаємо мета-дані, щоб не зберігати лише пробіли.
            $model->meta_title = trim((string) $model->meta_title);
            $model->meta_description = trim((string) $model->meta_description);

            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Не вдалося зберегти SEO-текст.');
            } else {
                if ($model->isEmpty()) {
                    if (!$model->isNewRecord) {
                        $model->delete();
                    }
                } elseif (!$model->save(false)) {
                    Yii::$app->session->setFlash('error', 'Не вдалося зберегти SEO-текст.');
                    return $this->render('update', [
                        'domain' => $domain,
                        'domainLabel' => $domains[$domain],
                        'model' => $model,
                    ]);
                }

                Yii::$app->session->setFlash('success', 'SEO-текст для головної сторінки оновлено.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'domain' => $domain,
            'domainLabel' => $domains[$domain],
            'model' => $model,
        ]);
    }

    /**
     * Формує перелік доменів для головної сторінки.
     *
     * @return array<string, string>
     */
    private function buildDomainOptions(): array
    {
        $domains = [];
        $mainDomain = defined('SITE_DOMAIN') ? SITE_DOMAIN : null;

        if ($mainDomain) {
            $domains[$this->normalizeDomain($mainDomain)] = $mainDomain;
        }

        $langDomains = Yii::$app->params['langDomains'] ?? [];
        foreach ($langDomains as $domain) {
            $domain = $this->normalizeDomain((string) $domain);
            if ($domain !== '') {
                $domains[$domain] = $domain;
            }
        }

        // Додаємо домени, які вже збережені в БД, щоб не втратити доступ до них.
        $storedDomains = MainPageSeoText::find()->select('domain')->distinct()->column();
        foreach ($storedDomains as $domain) {
            $domain = $this->normalizeDomain((string) $domain);
            if ($domain !== '' && !isset($domains[$domain])) {
                $domains[$domain] = $domain;
            }
        }

        return $domains;
    }

    /**
     * Нормалізує домен, щоб уникнути зайвих пробілів та різних регістрів.
     */
    private function normalizeDomain(string $domain): string
    {
        return mb_strtolower(trim($domain));
    }
}
