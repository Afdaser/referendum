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
} elseif ($poll->result_type == 3) {
    $result_type = 'pie';
} elseif ($poll->result_type == 4) {
    // Новий тип відповідає лінійному графіку Highcharts із динамікою відсотків у часі.
    $result_type = 'line';
} else {
    $result_type = 'bar';
} ?>
<?php if (!$poll->isShowResult() && empty($showChart)): ?>
    <form method="post" action="<?= Url::toRoute(['/poll/poll/vote']); ?>" class="poll-option-form">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>">
        <?php foreach($poll->pollOptions as $option):?>
            <div class="item_chose_poll">
                <button type="submit" class="radio_link poll-option-vote" name="option" value="<?= $option->id; ?>">
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
        'line' => $poll->getLineChartData(),
    ]);
    ?>
    <div
        class="inner_container_graph"
        id="container<?= $poll->id; ?>"
        data-chart-config='<?= $chartConfigJson; ?>'
        <?php if (!empty($lazyChart)): ?>data-chart-lazy="1"<?php endif; ?>
    ></div>
<?php endif;?>
