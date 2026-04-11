<?php

use common\helpers\StringHelper;
use frontend\helpers\Url;
use yii\helpers\Json;

/*
 * Раніше голосування виконувалось GET-посиланням на /poll/vote.
 * Тепер використовуємо POST-форму на /poll/poll/vote, щоб уникнути передачі option в URL.
 */

?>
<?php if ($poll->result_type == 2) {
    $result_type = 'column';
} elseif ($poll->result_type == 1) {
    $result_type = 'bar';
} else {
    $result_type = 'pie';
} ?>
<?php if(!$poll->isShowResult()):?>
    <form method="post" action="<?= Url::toRoute(['/poll/poll/vote']); ?>" class="poll-option-form">
        <!-- Один POST-формат на блок опитування, щоб не дублювати форму для кожної опції. -->
        <!-- Відправляємо голос POST-запитом, щоб не показувати ідентифікатор опції в адресному рядку. -->
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>">
        <?php foreach($poll->pollOptions as $option):?>
            <div class="item_chose_poll">
                <button type="submit" class="radio_link poll-option-vote" name="option" value="<?= $option->id; ?>">
                    <!-- Новий чіткий індикатор вибору без зображень. -->
                    <span class="radio_indicator"></span>
                    <span class="link_text"><?= (YII_ENV != 'dev') ? '' : "[{$option->id}]:"; ?><?= $option->title; ?></span>
                </button>
            </div>
        <?php endforeach;?>
    </form>
    <div class="item_chose_poll see_results">
        <form method="post" action="<?= Url::toRoute(['/poll/poll/view', 'id' => $poll->id]); ?>">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>">
            <input type="hidden" name="result" value="1">
            <button type="submit" class="radio_link poll-see-results">
                <!-- Новий чіткий індикатор вибору без зображень. -->
                <span class="radio_indicator"></span>
                <span class="link_text"><?= Yii::t('poll', 'Побачити результати'); ?></span>
            </button>
        </form>
    </div>
<?php else:?>
    <?php
    // Формуємо JSON-дані для централізованого рендера в окремому JS-файлі без inline-скриптів.
    $chartConfigJson = Json::htmlEncode([
        'category' => $result_type,
        'id' => 'container' . $poll->id,
        'title' => (string)$poll->title,
        'series' => StringHelper::formatForBarAjax($chartData)['series'] ?? [],
        'pie' => StringHelper::formatForPieAjax($chartData),
    ]);
    ?>
    <div
        class="inner_container_graph"
        id="container<?= $poll->id; ?>"
        data-chart-config='<?= $chartConfigJson; ?>'
    ></div>
<?php endif;?>
