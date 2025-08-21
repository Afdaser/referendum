<div class="modal-body">
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Діяльність'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][activity]"><?php echo $interests?$interests->activity:"";?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Інтереси'); ?><br>
            <span class="sub_text">
                <?php echo Yii::t("user", 'Перелічіть декілька цікавих'); ?><br> <?php echo Yii::t("user", 'для вас тем, наприклад'); ?>:<br> <?php echo Yii::t("user", 'технології, музика, фото'); ?>.
            </span>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][interests]"><?php echo $interests?$interests->interests:"";?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Улюблена музика'); ?><br>
            <span class="sub_text">
                <?php echo Yii::t("user", 'Улюблені гурти та'); ?><br> <?php echo Yii::t("user", 'виконавці'); ?>
            </span>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][music]"><?php echo $interests?$interests->music:"";?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Улюблені фільми'); ?><br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][films]"><?php echo $interests?$interests->films:"";?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Улюблені телешоу'); ?>:<br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][shows]"><?php echo $interests?$interests->shows:"";?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Улюблені книги'); ?><br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][books]"><?php echo $interests?$interests->books:"";?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?php echo Yii::t("user", 'Улюблені ігри'); ?><br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests][games]"><?php echo $interests?$interests->games:"";?></textarea>
        </div>
    </div>
</div>