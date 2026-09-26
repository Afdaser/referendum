<?php // Селектор перемикає країну піддомену; назва не ототожнює країну з мовою інтерфейсу. ?>
<select
    class="language_select"
    aria-label="<?= \yii\helpers\Html::encode(Yii::t('main', 'Країна піддомену')); ?>"
    onchange='document.location.href = $(this).val()'
>
    <?php foreach($languages as $language):?>
        <?php // Показуємо назви країн, яким відповідають доступні піддомени. ?>
        <option <?= (Yii::$app->language == $language->locale) ? ' selected="selected"' : ''; ?> value="<?= Yii::$app->urlManager->createLangUrl($language->name, "/"); ?>"><?= \common\models\Language::getCountryTitle($language); ?></option>
    <?php endforeach;?>
</select>
