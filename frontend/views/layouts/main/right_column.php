<?php

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
        <?php // Блок з повідомленням про помилки на сайті свідомо видалено. ?>
	</div>
</div>
