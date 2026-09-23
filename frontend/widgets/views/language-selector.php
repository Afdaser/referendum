<?php // Доступна назва пояснює призначення селектора незалежно від видимого переліку країн. ?>
<select
    class="language_select"
    aria-label="<?= \yii\helpers\Html::encode(Yii::t('main', 'Мова сайту')); ?>"
    onchange='document.location.href = $(this).val()'
>
    <?php foreach($languages as $language):?>
        <?php // Показуємо країни у перемикачі мов замість назв мов. ?>
        <option <?= (Yii::$app->language == $language->locale) ? ' selected="selected"' : ''; ?> value="<?= Yii::$app->urlManager->createLangUrl($language->name, "/"); ?>"><?= \common\models\Language::getCountryTitle($language); ?></option>
    <?php endforeach;?>
</select>
