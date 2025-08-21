<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */
/** @var yii\widgets\ActiveForm $form */
// <div class="poll-form" >
?>

<div class="search_b" style="border:2px dashed green;">
<?php
//        $requestQueryParams = Yii::$app->request->queryParams;
//        if(empty($requestQueryParams['BatchSearch']['status_id'])){
////            $dataProvider->query->onlyWorkStatuses();
////            $requestQueryParams['BatchSearch']['status_id'] = 1;
//        }
//        $searchForm->load($requestQueryParams);
//        $this->data['searchForm']->load($requestQueryParams);
//        $this->data['searchForm']->load($this->request);
//echo '<h2>'.__METHOD__.'</h2>';
//echo '<h2>requestQueryParams:</h2><pre>';
//var_dump($requestQueryParams);
//
//echo '<h2>searchForm:</h2>';
//var_dump($model);
//echo '</pre>';
//die(__FILE__.'#'.__LINE__);

?> 
</div>

<div class="search_b" style="border:2px dashed green;">


    <?php $form = ActiveForm::begin(); ?>

<?=
        $form->field($model, 'text')
        ->textInput(['autofocus' => false])
        ->label(false)
        ->input('text', ['placeholder' => Yii::t("main", 'Пошук') . '...'])
?>

    <?= $form->field($model, 'search_in_title')->hiddenInput()->label(false); ?>

<?php /* * / ?>
    <?= $form->field($model, 'search_in_title')
            ->checkbox(['options'])
            ->label(false)
            ->input('checkbox', ['placeholder' => Yii::t("main", 'Пошук') . '...'])
/* */            ?>


    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'OK'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
