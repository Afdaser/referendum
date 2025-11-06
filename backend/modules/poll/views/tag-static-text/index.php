<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use common\models\Language;
use common\models\Tag;
use common\models\TagStaticText;
use common\models\search\TagStaticTextSearch;

/** @var yii\web\View $this */
/** @var TagStaticTextSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статичний текст на тегах';
$this->params['breadcrumbs'][] = $this->title;

$tagFilter = Tag::find()->select('name')->orderBy('name')->indexBy('id')->column();
$languageFilter = Language::dropDownAllItems();
?>
<div class="tag-static-text-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Додати текст', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'tag_id',
                'label' => 'Тег',
                'value' => static function (TagStaticText $model) {
                    return $model->tag ? $model->tag->name : null;
                },
                'filter' => $tagFilter,
            ],
            [
                'attribute' => 'language_id',
                'label' => 'Мова',
                'value' => static function (TagStaticText $model) {
                    return $model->language ? $model->language->title : null;
                },
                'filter' => $languageFilter,
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime'],
                'value' => static function (TagStaticText $model) {
                    return $model->updated_at ? $model->updated_at : $model->created_at;
                },
            ],
            [
                'attribute' => 'content',
                'format' => 'ntext',
                'value' => static function (TagStaticText $model) {
                    $text = preg_replace('/@[a-z0-9_]+/i', '', $model->content);
                    $text = strip_tags($text);
                    return mb_substr($text, 0, 200) . (mb_strlen($text) > 200 ? '…' : '');
                },
                'contentOptions' => ['style' => 'max-width: 420px; white-space: normal;'],
            ],
            [
                'class' => ActionColumn::class,
            ],
        ],
    ]) ?>
</div>
