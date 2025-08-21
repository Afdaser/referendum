<?php

/* @var $this SiteController */
/* @var $search SearchForm */

Yii::$app->params['category'] = $category;
Yii::$app->params['model'] = isset($search) ? $search : null;
?>
<div class="col-md-8">
    <div class="row right_cut_row">

        <?php if (count($this->context->menu) > 3): ?><div class="my_account_tabs"><?php endif; ?>
        <?php /*
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->context->menu,
            'encodeLabel' => false,
            'htmlOptions' => array('class' => 'nav nav-tabs', "role" => "tablist"),
        )); /* */
        ?>
            <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>
        <div style="border:3px dashed red;">
            <h4><?= __FILE__; ?></h4>
            <pre>
                <?php var_dump($this->context->menu); ?>
            </pre>
        </div>
        <?php if (!Yii::$app->user->isGuest): ?>
        <?= $this->render('/user/registration/registration', array('user'=>isset($user)?$user: Yii::$app->user->getUser(),'error'=>isset($error)?$error:NULL));?>
        <?php endif; ?>
        <?php if ($category == 'newComments'): ?>
            <div class="tab-content">
                <?php $this->renderPartial('/user/_new_comments', array('polls' => $polls)); ?>
            </div>
        <?php elseif ($category == 'newAnswers'): ?>
            <div class="tab-content">
                <?php $this->renderPartial('/user/_new_answers', array('comments' => $comments)); ?>
            </div>
        <?php elseif ($category != 'profile'): ?>
            <?php if ($category == 'own' && !count($polls)): ?>
                <?php if (!Yii::$app->user->isGuest): ?>
                    $this->renderPartial('polls/_myPollsEmpty');
                <?php endif; ?>
            <?php elseif ($category != 'actual' || !Yii::$app->user->isGuest): ?>
                <?php
                $this->renderPartial('filters/_sort', array(
                    'category' => $category,
                    'limit' => $limit,
                    'tag' => $tag,
                    'sort' => $sort,
                    'user' => $user,
                    'pollsCount' => $pollsCount,
                    'search' => $search,
                    'period' => $period
                ));
                ?>
                <?php $this->renderPartial('polls/_mainPolls', array('polls' => $polls, 'limit' => $limit, 'sort' => $sort)); ?>
                <?php
                $this->renderPartial('filters/_paginator', array(
                    'limit' => $limit,
                    'sort' => $sort,
                    'pages' => $pages,
                    'tag' => $tag,
                    'user' => $user,
                    'category' => $category,
                    'period' => $period
                ));
                ?>
            <?php endif; ?>
            <?php $this->renderPartial('//poll/_newPoll', array('model' => $pollModel)); ?>
        <?php else: ?>
            <?php $this->renderPartial('/user/profile', array('user' => $user, 'error' => $error, 'profileCategory' => $profileCategory, 'other' => $other)); ?>
<?php endif; ?>
    </div>
</div>

<?php if (!Yii::$app->user->isGuest): ?>
    <script>
    <?php /* Yii1 remove: if (!Yii::$app->user->getUser()->is_active): */ ?>
    <?php if (!Yii::$app->user->identity->isActive): ?>
            $(function () {
                //$('#my_profile_main').modal('show');
            });
    <?php elseif (isset($other)): ?>
        <?php if ($other): ?>
                $(function () {
                    // $('#my_profile_all').modal('show');
                });
        <?php endif; ?>
    <?php endif; ?>

        $(document).on('click', '.skip_btn', function () {
            $('#my_profile_all').modal('hide');
        });
    </script>
<?php endif; ?>