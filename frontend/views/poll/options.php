<?php

//use yii\helpers\Url;
use frontend\helpers\Url;
use yii\web\View;

?>
<?php if(!$poll->isShowResult()):?>
    <?php foreach($poll->pollOptions as $option): ?>
        <div class="item_chose_poll">
            <form method="post" action="<?= Url::toRoute(['/poll/poll/vote']); ?>" class="poll-option-form">
                <!-- Відправляємо голос POST-запитом, щоб URL не містив параметрів голосування. -->
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>">
                <input type="hidden" name="option" value="<?= $option->id; ?>">
                <button type="submit" class="radio_link poll-option-vote">
                    <!-- Індикатор вибору без зображень для кращої читабельності. -->
                    <span class="poll_option_indicator"></span>
                    <span class="link_text"><?= $option->title; ?></span>
                </button>
            </form>
        </div>
    <?php endforeach; ?>
    <div class="item_chose_poll see_results">
        <form method="post" action="<?= Url::toRoute(['/poll/poll/view', 'id' => $poll->id]); ?>">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>">
            <input type="hidden" name="result" value="1">
            <button type="submit" class="radio_link poll-see-results">
                <!-- Індикатор вибору без зображень для кращої читабельності. -->
                <span class="poll_option_indicator"></span>
                <span class="link_text"><?= Yii::t('poll', 'Побачити результати'); ?></span>
            </button>
        </form>
    </div>
<?php else: ?>
    <div class="inner_container_graph" id="container<?= $poll->id; ?>"></div>
<?php endif; ?>

<?php
if ($poll->result_type == 2) {
    $result_type = 'column';
} elseif ($poll->result_type == 1) {
    $result_type = 'bar';
} else {
    $result_type = 'pie';
}


$jsRenderChart = <<<JS_RENDER_CHART
/* DEV.JS f=~/frontend/views/poll/options.php */
    $(function () {
        renderChart('{$result_type}','container{$poll->id}','{$poll->title}',[{$bar['series']}],[{$pie}]);
    });
JS_RENDER_CHART;
$script = <<<JS_FINAL
jQuery(document).ready(function() {
{$jsRenderChart}

});
JS_FINAL;

$this->registerJs($script, View::POS_END);


/* */ ?>

<?php /* * / ?>
<script>
    <?php if($poll->result_type == 2){ $result_type = 'column'; }else if($poll->result_type == 1){ $result_type = 'bar';} else { $result_type = 'pie'; } ?>

    $(function () {
        renderChart('<?= $result_type; ?>','container<?= $poll->id; ?>','<?= $poll->title;?>',[<?= $bar['series']; ?>],[<?= $pie;?>]);
    });
</script>
<?php /* */ ?>
