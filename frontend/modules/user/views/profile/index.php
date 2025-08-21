<?php
/** @var yii\web\View $this */
/*
  <h1>profile/index</h1>

  <p>
  You may change the content of this page by modifying
  the file <code><?= __FILE__; ?></code>.
  </p>
  /* */
?>

<?php

use yii\widgets\Menu;

/** @var yii\web\View $this */
//$this->title = 'My Yii Application';
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <?php if (count($this->context->menu)): ?>
            <?php if (count($this->context->menu) > 3): ?><div class="my_account_tabs"><?php endif; ?>
            <?=
            Menu::widget([
                'items' => $this->context->menu,
                'encodeLabels' => false,
//                        'htmlOptions' => ['class' => 'nav nav-tabs', 'role' => 'tablist'],
                'options' => ['class' => 'nav nav-tabs', 'role' => 'tablist'],
            ]);
            /*
              $this->widget('zii.widgets.CMenu', array(
              'items' => $this->context->menu,
              'encodeLabel' => false,
              'htmlOptions' => array('class' => 'nav nav-tabs', "role" => "tablist"),
              )); /* */
            ?>
            <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>
            <?php /* else: * / ?>
            <ul class="nav nav-tabs" role="tablist" id="yw0">
                <li><a href="/">Hot topics</a></li>
                <li><a href="/site/actualPolls">Topics relevant to you</a></li>
                <li><a href="/site/myPolls">Your polls</a></li>
            </ul>
            <hr>
            <h2 style="border:2px dashed red;">count($this->menu)>3:</h2>
            <hr>
        <?php /* */ ?>
        <?php endif; ?>
        <?= $this->render('/profile/profile_tabs', [
            'category' => $category,
            'profileCategory' => $profileCategory,
            'user'=>$user,
            'error'=>$error,
            'other' => $other,
                ]); ?>
        <?php /* */ ?>
         <?php if (0 == 'dev'): // #DEV01 ?>
        <div class="chart_b user_page_b" style="border:2px dashed red;">
            <div class="top_b_chart">
                <a class="btn_prev_var" href="<?php echo Yii::$app->request->referrer; ?>"><?php echo Yii::t("poll", 'Назад'); ?></a>
            </div>
            <div class="my_profile_b">
                <div class="profile_name">
                    <h2>Lang: <?= Yii::$app->language; ?></h2>
                </div>
                <h2 style="color:red;"><?= __FILE__ ?></h2>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
