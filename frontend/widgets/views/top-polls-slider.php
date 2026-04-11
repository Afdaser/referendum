<?php /** @var $activePolls Poll[] */ ?>
<div class="container">
    <div class="row">
        <div class="slider_top_b">
            <div class="slider_controls">
                <a class="slider_next" href="#"><?php echo Yii::t("poll", 'Next'); ?></a>
                <a class="slider_prev" href="#"><?php echo Yii::t("poll", 'Prev'); ?></a>
            </div>
            <div class="slider">
                <?php foreach ($activePolls as $poll): ?>
                    <div class="slide">
                        <div class="inner_slide_b">
                            <div class="item_normal">
                                <?php // Тримаємо посилання на один рядок, щоб HTML у вихідному коді сторінки був компактнішим і читабельнішим. ?>
                                <a class="poll_title_link" href="<?php echo $poll->url; ?>"><?php echo $poll->title; ?></a>
                            </div>
                            <?php // Інформаційний шар наводить статистику і не блокує взаємодію з посиланням на назву. ?>
                            <div class="item_hover clearfix">
                                <div class="left_info_b">
                                    <span class="lock_item"></span>
                                    <span class="rating_text_left_info">
                                        <span class="caret_top"></span>
                                        <?php echo $poll->rating; ?>
                                        <span class="caret_down"></span>
                                    </span>
                                </div>
                                <div class="middle_text_info">
                                    <?php echo Yii::t("poll", 'коментарів'); ?>: <?php echo count($poll->pollComments); ?><br>
                                    <?php // Виводимо сумарну кількість голосів (зареєстровані + гості), щоб відображення у хедері було коректним. ?>
                                    <?php echo Yii::t("poll", 'голосів'); ?>: <?php echo $poll->countPollOptionsVoters; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
