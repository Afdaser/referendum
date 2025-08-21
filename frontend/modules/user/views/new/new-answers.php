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
            <?= $this->render('_new_answers', ['comments' => $comments]); ?>
        </div>
    </div>
</div>