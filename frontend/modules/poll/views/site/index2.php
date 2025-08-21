<?php

use yii\web\View;
use yii\widgets\Menu;
use frontend\widgets\WPollList;
use common\widgets\Alert;
//     

//use Yii;
//use yii\widgets\BaseListView AS BaseWidget;
//use yii\helpers\Html;
//use yii\helpers\Url;
//use app\models\User;


/** @var yii\web\View $this */
// $qty = $dataProvider->count;
?>
<div class="col-md-8">
    <div class="row">
        <?= Alert::widget(); ?>
    </div>
    <div class="row right_cut_row">
        <?=
        WPollList::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'menu' => $this->context->menu,
            'data' => $params,
        ]);
        ?>

        <?php /* if (count($this->context->menu)): ?>
            <?php if (count($this->context->menu) > 3): ?><div class="my_account_tabs"><?php endif; ?>
            <?=
            Menu::widget([
                'items' => $this->context->menu,
//                        'encodeLabel' => false,
//                        'htmlOptions' => ['class' => 'nav nav-tabs', 'role' => 'tablist'],
                'options' => ['class' => 'nav nav-tabs', 'role' => 'tablist'],
            ]);
            ?>
                <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>
        <?php endif; /* */ ?>

        <?php /* if ($qty) : ?>
            <?=
            WPollList::widget([
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'menu' => $this->context->menu,
            'data' => $params,
//                'data' => [
//                    'category' => $category,
//                    'limit' => $limit,
//                    'tag' => $tag ?? '',
//                    'sort' => $sort,
//                    'period' => $period,
//                ]
            ])
            ?>
        <?php else: ?>
            <div class="chart_b" style="margin-bottom: 5px;">
                <h4><?= Yii::t('app', 'No matching results'); ?></h4>
                <p>
                    You may change the content of this page by modifying
                    the file <code><?= __FILE__; ?></code>.
                </p>
            </div>
        <?php endif; /* */ ?>
    </div>
</div>

<?php

if(!empty($forcePollModal)){
    // .create_new_poll.my_profile
    // .left_btn_b > a:nth-child(1)
$scriptPoll = '$(".create_new_poll.my_profile").trigger("click");';
        $script = <<<JS_FINAL
jQuery(document).ready(function() {
{$scriptPoll}

});
JS_FINAL;

        $this->registerJs($script, View::POS_END);

//    die(__FILE__.'#'.__LINE__.'+ forcePollModal=['.forcePollModal.']');
}
?>