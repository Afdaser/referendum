<select class="language_select" onchange='document.location.href = $(this).val()'>
    <?php foreach($languages as $language):?>
        <option <?= (Yii::$app->language == $language->locale) ? ' selected="selected"' : ''; ?> value="<?= Yii::$app->urlManager->createLangUrl($language->name, "/"); ?>"><?= $language->title;?></option>
    <?php endforeach;?>
</select>