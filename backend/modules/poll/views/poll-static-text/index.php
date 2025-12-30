<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\PollSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статичний текст опитувань';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['/poll/poll/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="poll-static-text-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Редагуємо статичний текст та мета-теги окремо від основної форми опитування. ?>
        <?= Html::tag('span', 'Оберіть опитування для редагування статичного тексту.', ['class' => 'text-muted']) ?>
    </p>

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            'poll_language_id',
            [
                'label' => 'Статичний текст',
                'value' => static function ($model) {
                    /** @var common\models\Poll $model */
                    return $model->staticText ? 'Заповнено' : 'Немає';
                },
                'filter' => false,
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{update}',
                'urlCreator' => static function ($action, $model) {
                    /** @var common\models\Poll $model */
                    return ['update', 'id' => $model->id];
                },
            ],
        ],
    ]) ?>

</div>
