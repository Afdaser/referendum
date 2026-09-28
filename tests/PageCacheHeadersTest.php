<?php

use frontend\components\PageCacheHeaders;
use PHPUnit\Framework\TestCase;
use yii\web\Request;
use yii\web\Response;

class PageCacheHeadersTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        // Response використовує кодування активного застосунку під час ініціалізації.
        new \yii\console\Application([
            'id' => 'page-cache-headers-test',
            'basePath' => dirname(__DIR__),
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        Yii::$app = null;
    }

    public function testGuestHtmlPageIsCachedPrivatelyForOneWeek(): void
    {
        $request = $this->createRequest('GET');
        $response = new Response(['format' => Response::FORMAT_HTML, 'statusCode' => 200]);
        $before = time() + PageCacheHeaders::MAX_AGE;

        PageCacheHeaders::apply($request, $response, true);

        self::assertSame('private, max-age=604800', $response->headers->get('Cache-Control'));
        $expires = strtotime($response->headers->get('Expires'));
        // Даємо одну секунду допуску на перехід системного часу між обчисленнями.
        self::assertGreaterThanOrEqual($before, $expires);
        self::assertLessThanOrEqual(time() + PageCacheHeaders::MAX_AGE + 1, $expires);
    }

    /**
     * @dataProvider nonCacheableResponseProvider
     */
    public function testSensitiveResponsesRemainUncacheable(
        string $method,
        string $format,
        int $statusCode,
        bool $isGuest
    ): void {
        $request = $this->createRequest($method);
        $response = new Response(['format' => $format, 'statusCode' => $statusCode]);

        PageCacheHeaders::apply($request, $response, $isGuest);

        self::assertSame('no-store, no-cache, must-revalidate', $response->headers->get('Cache-Control'));
        self::assertSame('Thu, 01 Jan 1970 00:00:00 GMT', $response->headers->get('Expires'));
    }

    public function nonCacheableResponseProvider(): array
    {
        return [
            'authenticated page' => ['GET', Response::FORMAT_HTML, 200, false],
            'form submission' => ['POST', Response::FORMAT_HTML, 200, true],
            'JSON response' => ['GET', Response::FORMAT_JSON, 200, true],
            'error page' => ['GET', Response::FORMAT_HTML, 404, true],
        ];
    }

    private function createRequest(string $method): Request
    {
        // Yii читає HTTP-метод із серверного оточення, як і під час реального запиту.
        $_SERVER['REQUEST_METHOD'] = $method;
        $request = new Request();

        return $request;
    }
}
