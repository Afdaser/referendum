<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language[] $languages */
/** @var common\models\TagStaticText[] $models */
/** @var array $tokens */
/** @var array $groupedLanguages */

$this->title = 'Статичний текст на тегах';
?>
<div class="tag-static-text-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна вказати власний HTML-текст для блоку на сторінці тегу. Використовуйте змінні, щоб підставити актуальні дані:
    </p>
    <ul>
        <?php foreach ($tokens as $token => $description): ?>
            <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Порожнє поле вилучає текст для відповідної мови та вмикає стандартний автоматичний опис.
    </p>

    <?php if (empty($groupedLanguages)): ?>
        <div class="alert alert-warning">
            Перекладів для жодної підмови не знайдено. Додайте мовні файли або налаштуйте локалі, щоб увімкнути редагування.
        </div>
    <?php else: ?>
        <?php $form = ActiveForm::begin(); ?>

        <ul class="nav nav-tabs" role="tablist">
            <?php $isFirst = true; ?>
            <?php foreach ($groupedLanguages as $groupKey => $groupData): ?>
                <li class="<?= $isFirst ? 'active' : ''; ?>" role="presentation">
                    <a href="#tab-<?= Html::encode($groupKey); ?>" aria-controls="tab-<?= Html::encode($groupKey); ?>" role="tab" data-toggle="tab">
                        <?= Html::encode($groupData['label']); ?>
                        <?php if (count($groupData['languages']) > 1): ?>
                            <span class="badge" title="Кількість підмов у групі"><?= count($groupData['languages']); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php $isFirst = false; ?>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" style="margin-top: 20px;">
            <?php $isFirstPane = true; ?>
            <?php foreach ($groupedLanguages as $groupKey => $groupData): ?>
                <div role="tabpanel" class="tab-pane <?= $isFirstPane ? 'active' : ''; ?>" id="tab-<?= Html::encode($groupKey); ?>">
                    <?php foreach ($groupData['languages'] as $language): ?>
                        <?php $model = $models[$language->id]; ?>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <?= Html::encode($language->title); ?>
                                    <small class="text-muted">(<?= Html::encode($language->locale); ?>)</small>
                                </h3>
                            </div>
                            <div class="box-body">
                                <?= $form->field($model, "[{$language->id}]language_id")->hiddenInput()->label(false); ?>
                                <?= $form->field($model, "[{$language->id}]content")
                                    ->textarea(['rows' => 8, 'class' => 'form-control'])
                                    ->hint('Можна використовувати HTML та змінні з переліку вище.'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php $isFirstPane = false; ?>
            <?php endforeach; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
