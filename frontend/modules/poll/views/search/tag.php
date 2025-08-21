<?php

use frontend\widgets\WPollList;

//use Yii;
//use yii\widgets\BaseListView AS BaseWidget;
//use yii\helpers\Html;
//use yii\helpers\Url;
//use app\models\User;


/** @var yii\web\View $this */
$qty = $dataProvider->count;
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <h1>Latest <?= $tag; ?> public opinion polls</h1>
<?php if ($qty) : ?>
    <?=
    WPollList::widget([
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'searchForm' => $searchForm,
//                    'itemOptions' => ['class' => 'item'],
    ])
    ?>
<?php else: ?>

            <div class="chart_b" style="margin-bottom: 5px;">
                <h1>search/tag</h1>
                <h4><?= Yii::t('app', 'No matching results'); ?></h4>
                <p>
                    You may change the content of this page by modifying
                    the file <code><?= __FILE__; ?></code>.
                </p>



            </div>

<?php endif; ?>
<?php /* */ ?>

    </div>
    </div>

<?php /* */
?>