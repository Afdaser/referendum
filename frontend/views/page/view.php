<div class="col-md-8">
    <div class="row right_cut_row">
        <?php if (count($this->context->menu)): ?>
            <?php if (count($this->context->menu) > 3): ?><div class="my_account_tabs"><?php endif; ?>
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'items' => $this->context->menu,
                'encodeLabel' => false,
                'htmlOptions' => array('class' => 'nav nav-tabs', "role" => "tablist"),
            ));
            ?>
                <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>
        <?php endif; ?>
        <div class="chart_b user_page_b">
            <div class="top_b_chart">
                <a class="btn_prev_var"
                   href="<?php echo Yii::$app->request->referrer; ?>"><?php echo Yii::t("poll", 'Назад'); ?></a>
            </div>
            <div class="my_profile_b">
                <div class="profile_name">
                    <?php echo!empty($pageData['name']) ? $pageData['name'] : Yii::t("main", 'Партнери'); ?>
                </div>
                <div>
                    <?php echo!empty($pageData['content']) ? $pageData['content'] : Yii::t("static", StringHelper::PARTNERS); ?>
                </div>
            </div>
        </div>
    </div>
</div>