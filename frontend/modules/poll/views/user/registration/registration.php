<?php

use frontend\helpers\Url;
//use common\helpers\StringHelper;
//use common\models\User;
//use common\models\Country;
//use common\models\Language;

?>
<?= (YII_ENV != 'dev') ? '' : "<!-- #DEV24-06 \n". __FILE__."\n -->"; ?>
<?php if(YII_ENV == 'dev') :?>
<div style="border: 3px dotted red;">
    <h2>MODAL old:</h2>
    <?= __FILE__; ?>
</div>
<?php endif;?>


<!-- Modal DEV2404_M08 -->
<?php $this->render('/user/registration/_main', array('user'=>$user,'error'=>$error)); ?>
<!-- Modal DEV2404_M09 -->
<div class="modal new_poll" id="my_profile_all" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only"><?= Yii::t("user", 'Close'); ?></span>
                </button>
                <div class="modal_title"><?= Yii::t("user", 'Мій профіль'); ?></div>
            </div>
            <?php $this->render('/user/registration/_head', array()); ?>
            <div class="tabs_popup_b">
                <ul class="nav nav-tabs" role="tablist">
                    <li><a href="#"><?= Yii::t("user", 'Основне'); ?></a></li>
                    <li class="active"><a href="#interest" role="tab" data-toggle="tab"><?= Yii::t("user", 'Інтереси'); ?></a></li>
                    <li><a href="#education" role="tab" data-toggle="tab"><?= Yii::t("user", 'Освіта'); ?></a></li>
                    <li><a href="#career" role="tab" data-toggle="tab"><?= Yii::t("user", 'Кар’єра'); ?></a></li>
                </ul>
            </div>
            <form method="post" action="<?= Url::toRoute('/user/registration', array('category'=>'other')); ?>">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="interest">
                        <?php $this->render('/user/registration/_interests', array('interests'=>isset($user->interests)?$user->interests:null)); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="education">
                        <?php $this->render('/user/registration/_education', array('secondaryEducation'=>isset($user->secondaryEducation)?$user->secondaryEducation:null,'highEducation'=>isset($user->highEducation)?$user->highEducation:null)); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="career">
                        <?php $this->render('/user/registration/_career', array('career'=>isset($user->career)?$user->career:null)); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="sub_title_modal"></div>
                    <div class="btn_b_modal">
                        <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t("user", 'ЗБЕРЕГТИ'); ?></button>
                    </div>
                    <a href="#" class="create_new_poll" data-dismiss="modal"><?= Yii::t("user", 'Скасувати'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>


<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV24-06 -->'; ?>