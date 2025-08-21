<div class="tab-pane active" id="my_account_change">
    <div class="sort_b clearfix">
        <div class="tabs_popup_b">
            <ul class="nav nav-tabs" role="tablist">
                <li class="<?php if($profileCategory == 'main'):?>active<?php endif;?>"><a href="#main_acc"  role="tab" data-toggle="tab"><?php echo Yii::t("user", 'Основне'); ?></a></li>
                <li class="<?php if($profileCategory == 'interests'):?>active<?php endif;?>"><a href="#interest_acc" role="tab" data-toggle="tab"><?php echo Yii::t("user", 'Інтереси'); ?></a></li>
                <li class="<?php if($profileCategory == 'education'):?>active<?php endif;?>"><a href="#education_acc" role="tab" data-toggle="tab"><?php echo Yii::t("user", 'Освіта'); ?></a></li>
                <li class="<?php if($profileCategory == 'career'):?>active<?php endif;?>"><a href="#career_acc" role="tab" data-toggle="tab"><?php echo Yii::t("user", 'Кар’єра'); ?></a></li>
                <li class="<?php if($profileCategory == 'settings'):?>active<?php endif;?>"><a href="#settings" role="tab" data-toggle="tab"><?php echo Yii::t("user", 'Параметри'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="bottom_content_tabs smaller_pad">
        <div class="tab-content">
            <div class="tab-pane <?php if($profileCategory == 'main'):?>active<?php endif;?>" id="main_acc">
                <?php $this->renderPartial('/user/_main', array('user'=>$user,'error'=>$error,'category'=>$profileCategory,'other'=>$other)); ?>
            </div>
            <div class="tab-pane <?php if($profileCategory == 'interests'):?>active<?php endif;?>" id="interest_acc">
                <?php $this->renderPartial('/user/_interests', array('interests'=>isset($user->interests)?$user->interests:null,'error'=>$error,'category'=>$profileCategory)); ?>
            </div>
            <div class="tab-pane <?php if($profileCategory == 'education'):?>active<?php endif;?>" id="education_acc">
                <?php $this->renderPartial('/user/_education', array('secondaryEducation'=>isset($user->secondaryEducation)?$user->secondaryEducation:null,'highEducation'=>isset($user->highEducation)?$user->highEducation:null,'error'=>$error,'category'=>$profileCategory)); ?>
            </div>
            <div class="tab-pane <?php if($profileCategory == 'career'):?>active<?php endif;?>" id="career_acc">
                <?php $this->renderPartial('/user/_career', array('career'=>isset($user->career)?$user->career:null,'error'=>$error,'category'=>$profileCategory)); ?>
            </div>
            <div class="tab-pane <?php if($profileCategory == 'settings'):?>active<?php endif;?>" id="settings">
                <?php $this->renderPartial('/user/_settings', array('user'=>$user,'error'=>$error,'category'=>$profileCategory)); ?>
            </div>
        </div>
    </div>
</div>