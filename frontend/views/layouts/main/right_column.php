<?php

use yii\web\View;
use frontend\widgets\WPollsSidebar;
use frontend\widgets\WUserSidebar;

/** @var $this Controller */ ?>

<!-- ~/frontend/views/layouts/main/right_column.php -->

<div class="col-md-4">
	<div class="row left_cut_row">
		<div class="auth_b">
                    <?php /* * / ?>
                    <h3 style="color:red;">WUserSidebar::widget();</h3>
                    <h4 style="color:blue;">~/frontend/views/layouts/main/right_column.php</h4>
                    <?php /* */ ?>
			<?= WUserSidebar::widget(); /* */ ?>
			<?php /* $this->widget('UserSidebar'); /* */ ?>
		</div>
		<div class="right_banner_b">
<?php /* OLD.adsbygoogle :
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php /* /OLD.adsbygoogle */?>
<!-- right -->
<?php if(YII_ENV == 'prod'): ?>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3234808971320300"
     data-ad-slot="2085995871"
     data-ad-format="auto"></ins>
<?php else: ?>
    <div <?= (YII_ENV == 'dev') ? ' style="border:2px dotted red;"' : '' ?>
        <h4>adsbygoogle</h4>
        data-ad-client="ca-pub-3234808971320300"<br>
         data-ad-slot="2085995871"
    </div>
<?php endif; ?>
<?php /* OLD.adsbygoogle :
<script>
//(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php /* /OLD.adsbygoogle */?>
		</div>
		<?= WPollsSidebar::widget(); ?>
		<?php /* $this->widget('PollsSidebar'); /* */  ?>
		<div class="social_grey_b">
			<div id="fb-root"></div>
			<div id="fb-root"></div>
		</div>
        <div class="social_grey_b">
            <?= Yii::t('main', 'Нашли ошибку на сайте? Выделите ее и нажмите Ctrl+Enter') ?>
        </div>
	</div>
</div>

<?php /*
<script>
    $(document).keydown(function(e) {
        if ((e.ctrlKey && e.keyCode == 13) || (e.metaKey && e.keyCode == 13)) {
            e.preventDefault();
            var text = "";
            if (window.getSelection) {
                text = window.getSelection().toString();
            } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
            }

            if (text !== '') {
                $.post('/site/submitError', {selection: text, pageUrl: document.location.href}, function() {
                    alert('<?= Yii::t('main', 'Спасибо! Сообщение об ошибке отправлено') ?>');
                });
            }
        }
    });
</script>
/* */ 

$alertMessage = Yii::t('main', 'Спасибо! Сообщение об ошибке отправлено');

$scriptKeyDown = <<<JS_KEYDOWN
    $(document).keydown(function(e) {
        if ((e.ctrlKey && e.keyCode == 13) || (e.metaKey && e.keyCode == 13)) {
            e.preventDefault();
            var text = "";
            if (window.getSelection) {
                text = window.getSelection().toString();
            } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
            }

            if (text !== '') {
                $.post('/site/submitError', {selection: text, pageUrl: document.location.href}, function() {
                    alert('{$alertMessage}');
                });
            }
        }
    });
JS_KEYDOWN;

$script = <<<JS_FINAL
jQuery(document).ready(function() {
{$scriptKeyDown}

});
JS_FINAL;

$this->registerJs($script, View::POS_END);
