<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\TagStaticText;

/** @var yii\web\View $this */
/** @var TagStaticText $model */
/** @var array<string,string> $placeholders */

$this->title = $model->tag ? $model->tag->name : $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст на тегах', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-static-text-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цей запис?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'tag_id',
                'label' => 'Тег',
                'value' => $model->tag ? $model->tag->name : null,
            ],
            [
                'attribute' => 'language_id',
                'label' => 'Мова',
                'value' => $model->language ? $model->language->title : null,
            ],
            [
                'attribute' => 'content',
                'format' => 'raw',
                'value' => Html::tag('pre', Html::encode($model->content), ['class' => 'pre-scrollable']),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <div class="alert alert-info">
        <p><strong>Доступні змінні:</strong></p>
        <ul class="list-unstyled">
            <?php foreach ($placeholders as $code => $description): ?>
                <li><code><?= Html::encode($code) ?></code> — <?= Html::encode($description) ?></li>
            <?php endforeach; ?>
        </ul>
        <p>Порада: додайте текст для всіх мов, щоб сторінка тегу відразу показувала правильний опис.</p>
    </div>
</div>
