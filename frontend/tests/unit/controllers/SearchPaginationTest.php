<?php

namespace frontend\tests\unit\controllers;

use frontend\modules\poll\controllers\SearchController;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\web\Application;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 4) . '/vendor/yiisoft/yii2/Yii.php';
require_once dirname(__DIR__, 4) . '/common/config/bootstrap.php';

class SearchPaginationTest extends TestCase
{
    public function testSearchSurvivesPaginationAndReloads()
    {
        $previousApp = Yii::$app;
        $app = new Application([
            'id' => 'search-pagination-test',
            'basePath' => dirname(__DIR__, 3),
            'params' => ['POLLS_LIMIT_MAIN_PAGE' => 10],
            'components' => [
                'request' => ['cookieValidationKey' => 'test', 'scriptUrl' => '/index.php'],
                'urlManager' => [
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                    'rules' => ['site/search' => 'poll/search/search'],
                ],
                'db' => [
                    'class' => 'yii\\db\\Connection',
                    'dsn' => getenv('DB_DSN'),
                    'username' => getenv('DB_USERNAME'),
                    'password' => getenv('DB_PASSWORD'),
                ],
            ],
        ]);
        $controller = new class('search', $app) extends SearchController {
            public function init()
            {
                $this->request = Yii::$app->request;
                $this->response = Yii::$app->response;
            }
            public function render($view, $params = []) { return $params; }
        };
        $app->controller = $controller;
        $filters = [
            'text' => 'Україна & освіта',
            'search_in_title' => '1',
            'search_in_tags' => '1',
            'country' => '1',
            'region' => '2',
        ];

        try {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $app->request->setQueryParams(['page' => 3, 'limit' => 10, 'sorting' => 'desc']);
            $app->request->setBodyParams(['SearchForm' => $filters]);
            $response = $controller->actionSearch();
            self::assertSame(303, $response->statusCode);
            parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $params);
            self::assertSame($filters, $params['SearchForm']);
            self::assertArrayNotHasKey('page', $params);
            self::assertSame('desc', $params['sorting']);

            $_SERVER['REQUEST_METHOD'] = 'GET';
            $app->request->setBodyParams([]);
            $where = null;
            foreach ([1, 2, 3, 3] as $page) {
                $params['page'] = $page;
                $app->request->setQueryParams($params);
                $result = $controller->actionSearch();
                self::assertSame($filters, $result['searchForm']->getAttributes());
                $query = $result['dataProvider']->query;
                if ($where === null) {
                    $where = $query->where;
                }
                self::assertSame($where, $query->where);
                self::assertContains('tags', $query->joinWith[0][0]);
                $pagination = $result['dataProvider']->pagination;
                $pagination->totalCount = 100;
                self::assertSame(($page - 1) * 10, $pagination->offset);
                parse_str(parse_url($pagination->createUrl($page), PHP_URL_QUERY), $next);
                self::assertSame($filters, $next['SearchForm']);
                self::assertSame((string) ($page + 1), $next['page']);
            }
        } finally {
            unset($_SERVER['REQUEST_METHOD']);
            Yii::$app = $previousApp;
        }
    }
}
