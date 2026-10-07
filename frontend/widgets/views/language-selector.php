<?php
// Поточна країна визначається доменом, а не мовою інтерфейсу.
$currentHost = strtolower((string) Yii::$app->request->hostName);
$mainHost = strtolower(SITE_DOMAIN);
$isWorldwide = $currentHost === $mainHost || $currentHost === 'www.' . $mainHost;
?>
<select
    class="language_select"
    aria-label="<?= \yii\helpers\Html::encode(Yii::t('main', 'Країна піддомену')); ?>"
    onchange='document.location.href = $(this).val()'
>
    <option <?= $isWorldwide ? ' selected="selected"' : ''; ?> value="<?= \yii\helpers\Html::encode(SITE_PROTOCOL . SITE_DOMAIN . '/'); ?>">Worldwide</option>
    <?php foreach($languages as $language):?>
        <?php // Показуємо назви країн, яким відповідають доступні піддомени. ?>
        <option <?= ($currentHost === strtolower($language->name . '.' . SITE_DOMAIN)) ? ' selected="selected"' : ''; ?> value="<?= Yii::$app->urlManager->createLangUrl($language->name, "/"); ?>"><?= \common\models\Language::getCountryTitle($language); ?></option>
    <?php endforeach;?>
</select>
