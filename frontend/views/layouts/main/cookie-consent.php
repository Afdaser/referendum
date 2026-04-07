<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */

// Не нашкодь: за замовчуванням лишаємо явний плейсхолдер, щоб URL політики не був прихований у коді.
$cookiePolicyUrl = (string) (Yii::$app->params['cookiePolicyUrl'] ?? '/replace-with-your-cookie-policy-url');
?>
<div
    id="cookie-consent-banner"
    class="cookie-consent"
    role="dialog"
    aria-live="polite"
    aria-label="Cookie consent"
    data-cookie-consent-banner
>
    <div class="cookie-consent__content">
        <p class="cookie-consent__text">
            We use cookies to support essential site functions, improve performance, and remember your preferences.
            You can accept all cookies or allow only essential cookies.
            <a
                href="<?= Html::encode($cookiePolicyUrl); ?>"
                class="cookie-consent__link"
                target="_blank"
                rel="noopener noreferrer"
            >Cookie Policy</a>
        </p>
        <div class="cookie-consent__actions">
            <button type="button" class="cookie-consent__btn cookie-consent__btn--essential" data-cookie-consent-action="essential">
                Essential only
            </button>
            <button type="button" class="cookie-consent__btn cookie-consent__btn--accept" data-cookie-consent-action="accept">
                Accept all
            </button>
        </div>
    </div>
</div>
