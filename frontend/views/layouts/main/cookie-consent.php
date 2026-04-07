<?php

use common\models\CookieConsentSetting;
use yii\helpers\Html;

/** @var \yii\web\View $this */

$host = (string) Yii::$app->request->hostName;
$locale = (string) Yii::$app->language;

// Пріоритет: налаштування з адмінки під конкретний хост/локаль.
$setting = CookieConsentSetting::resolveForRequest($host, $locale);

// Fallback лишаємо на випадок, якщо адмін ще не створив правила в БД.
$cookiePolicyUrl = $setting !== null
    ? (string) $setting->policy_url
    : (string) (Yii::$app->params['cookiePolicyUrl'] ?? '/replace-with-your-cookie-policy-url');
$bannerText = $setting !== null
    ? (string) $setting->banner_text
    : 'We use cookies to support essential site functions, improve performance, and remember your preferences.';
$essentialButtonLabel = $setting !== null
    ? (string) $setting->essential_button_label
    : 'Essential only';
$acceptButtonLabel = $setting !== null
    ? (string) $setting->accept_button_label
    : 'Accept all';
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
            <?= nl2br(Html::encode($bannerText)); ?>
            <a
                href="<?= Html::encode($cookiePolicyUrl); ?>"
                class="cookie-consent__link"
                target="_blank"
                rel="noopener noreferrer"
            >Cookie Policy</a>
        </p>
        <div class="cookie-consent__actions">
            <button type="button" class="cookie-consent__btn cookie-consent__btn--essential" data-cookie-consent-action="essential">
                <?= Html::encode($essentialButtonLabel); ?>
            </button>
            <button type="button" class="cookie-consent__btn cookie-consent__btn--accept" data-cookie-consent-action="accept">
                <?= Html::encode($acceptButtonLabel); ?>
            </button>
        </div>
    </div>
</div>
