<?php

//use yii\helpers\Url;
use frontend\helpers\Url;
use yii\web\View;

?>
<?php if(!$poll->isShowResult()):?>
    <?php foreach($poll->pollOptions as $option): ?>
        <div class="item_chose_poll">
            <a href="<?= Url::toRoute(['/poll/poll/vote', 'option' => $option->id ]); ?>" class="radio_link poll-option-vote"><span class="radio_circle"></span>
                <span class="link_text"><?= $option->title; ?></span>
            </a>
        </div>
    <?php endforeach; ?>
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