<div class="tab-pane active" id="my_account_change">
    <div class="sort_b clearfix">
        <div class="tabs_popup_b">
            <ul class="nav nav-tabs" role="tablist">
                <li class="<?php if ($profileCategory == 'main'): ?>active<?php endif; ?>"><a href="#main_acc"  role="tab" data-toggle="tab"><?= Yii::t('user', 'Основне'); ?></a></li>
                <li class="<?php if ($profileCategory == 'interests'): ?>active<?php endif; ?>"><a href="#interest_acc" role="tab" data-toggle="tab"><?= Yii::t('user', 'Інтереси'); ?></a></li>
                <li class="<?php if ($profileCategory == 'education'): ?>active<?php endif; ?>"><a href="#education_acc" role="tab" data-toggle="tab"><?= Yii::t('user', 'Освіта'); ?></a></li>
                <li class="<?php if ($profileCategory == 'career'): ?>active<?php endif; ?>"><a href="#career_acc" role="tab" data-toggle="tab"><?= Yii::t('user', 'Кар’єра'); ?></a></li>
                <li class="<?php if ($profileCategory == 'settings'): ?>active<?php endif; ?>"><a href="#settings" role="tab" data-toggle="tab"><?= Yii::t('user', 'Параметри'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="bottom_content_tabs smaller_pad">
        <div class="tab-content">
            <div class="tab-pane <?php if ($profileCategory == 'main'): ?>active<?php endif; ?>" id="main_acc">
                <?= $this->render('/profile/_main', ['user' => $user, 'profile' => $user->profile,  'error' => $error, 'category' => $profileCategory, 'other' => $other]); ?>
            </div>
            <div class="tab-pane <?php if ($profileCategory == 'interests'): ?>active<?php endif; ?>" id="interest_acc">
                <?= $this->render('/profile/_interests', ['interests' => $user->userInterest, 'error' => $error, 'category' => $profileCategory]); ?>
            </div>
            <div class="tab-pane <?php if ($profileCategory == 'education'): ?>active<?php endif; ?>" id="education_acc">
                <?= $this->render('/profile/_education', ['secondaryEducation' => $user->userSecondaryEducation, 'highEducation' => $user->userHighEducation, 'error' => $error, 'category' => $profileCategory]); ?>
            </div>
            <div class="tab-pane <?php if ($profileCategory == 'career'): ?>active<?php endif; ?>" id="career_acc">
                <?= $this->render('/profile/_career', ['career' => $user->userCareer, 'error' => $error, 'category' => $profileCategory]); ?>
            </div>
            <div class="tab-pane <?php if ($profileCategory == 'settings'): ?>active<?php endif; ?>" id="settings">
                <?= $this->render('/profile/_settings', array('user' => $user, 'error' => $error, 'category' => $profileCategory)); ?>
            </div>
            <?php /* */ ?>
        </div>
    </div>
</div>