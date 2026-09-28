<?php

namespace frontend\components;

use yii\web\Request;
use yii\web\Response;

/**
 * Встановлює безпечні заголовки браузерного кешу для HTML-сторінок.
 */
final class PageCacheHeaders
{
    public const MAX_AGE = 604800;

    /**
     * Дозволяє тижневе приватне кешування лише успішних сторінок для гостей.
     */
    public static function apply(Request $request, Response $response, bool $isGuest): void
    {
        $isPageRequest = in_array($request->method, ['GET', 'HEAD'], true)
            && $response->format === Response::FORMAT_HTML
            && $response->statusCode >= 200
            && $response->statusCode < 300;

        if ($isGuest && $isPageRequest) {
            // Cookie відокремлює гостьову копію від сторінки після входу користувача.
            $response->headers->add('Vary', 'Cookie');
            $response->headers->set('Cache-Control', 'private, max-age=' . self::MAX_AGE);
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + self::MAX_AGE) . ' GMT');
            return;
        }

        // Авторизовані, помилкові та не-HTML відповіді можуть містити персональні дані.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
        $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}
