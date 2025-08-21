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
                'options' => ['class' => 'nav nav-tabs', 'role' => 'tablist'],
            ]);
            ?>
                <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>
        <?php endif; ?>

      

        <div class="tab-content">
            <?= $this->render('_new_comments', ['polls' => $polls]); ?>
        </div>
<?php /* * / ?>
        <div class="chart_b user_page_b" style="border:3px dotted red;">
            <div class="top_b_chart">
                <a class="btn_prev_var"
                   href="<?= Yii::$app->request->referrer; ?>"><?= Yii::t('poll', 'Назад'); ?></a>
            </div>
            <div class="my_profile_b">
                <div class="profile_name">
                    <h2>Lang: <?= Yii::$app->language; ?></h2>
                </div>                    
                <div class="profile_name">
                    <h1 class="display-4">Congratulations!+</h1>
                    <h2 style="color:red;"><?= __FILE__ ?></h2>
                </div>


                <div class="body-content">

                    <div class="row" style="border:1px dashed red;">
                        <h1>new/new-comments</h1>

                        <p>
                            You may change the content of this page by modifying
                            the file <code><?= __FILE__; ?></code>.
                        </p>

                    </div>                         
                </div>
            </div>
        </div>
<?php /* */ ?>
    </div>
</div>