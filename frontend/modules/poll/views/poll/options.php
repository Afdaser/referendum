<?php

use frontend\helpers\Url;
use yii\web\View;

/*
 * old:
 * <?php echo Yii::$app->createUrl('/poll/vote',array('option'=>$option->id)); ?>
 * new:
 * <?= Url::toRoute('/poll/poll/vote', ['option'=>$option->id]); ?>
 * <a href="<?= Url::toRoute('/poll/poll/vote', ['option'=> $option->id]); ?>" class="radio_link poll-option-vote"><span class="radio_circle"></span>
 */

?>
<?php if(!$poll->isShowResult()):?>
    <?php foreach($poll->pollOptions as $option):?>
        <div class="item_chose_poll">
            <a href="<?= Url::toRoute(['/poll/poll/vote', 'option'=> $option->id]); ?>" class="radio_link poll-option-vote"><span class="radio_circle"></span>
                <span class="link_text"><?= (YII_ENV != 'dev') ? '' : "[{$option->id}]:"; ?><?= $option->title; ?></span>
            </a>
        </div>
    <?php endforeach;?>
<?php else:?>
    <div class="inner_container_graph" id="container<?= $poll->id; ?>"></div>
<?php endif;?>

<?php if($poll->result_type == 2){ $result_type = 'column'; }else if($poll->result_type == 1){ $result_type = 'bar';} else { $result_type = 'pie'; } ?>
<?php /* */ ?>
<script>

</script>

<?php 

$jsRenderChart = <<<JS_RENDER_CHART
/* DEV.JS f=~/frontend/modules/poll/views/poll/options.php */
$(function () {
        renderChart('{$result_type}','container{$poll->id}','{$poll->title}',[ {$bar['series']}],[{$pie}]);
});
JS_RENDER_CHART;
$script = <<<JS_FINAL
jQuery(document).ready(function() {
{$jsRenderChart}

});
JS_FINAL;

$this->registerJs($script, View::POS_END);


/* */ ?>
<?php
/* DEV.JS f=~/frontend/modules/poll/views/poll/options.php */
/*
<script>
    $(function () {
        renderChart('<?php echo $result_type; ?>','container<?php echo $poll->id; ?>','<?php echo $poll->title;?>',[<?php echo $bar['series']; ?>],[<?php echo $pie;?>]);
    });
</script>


<?php /* */ ?>
