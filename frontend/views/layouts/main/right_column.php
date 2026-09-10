<?php

use frontend\widgets\WPollsSidebar;
use frontend\widgets\WUserSidebar;
use yii\helpers\Html;

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
			<?php
			// Офіційні сторінки проєкту; за потреби посилання можна оновити без зміни розмітки блока.
			$socialLinks = [
				'Facebook' => ['url' => 'https://www.facebook.com/online.statistic', 'icon' => 'facebook'],
				'LinkedIn' => ['url' => 'https://www.linkedin.com/company/referendum-social/', 'icon' => 'linkedin'],
				'Instagram' => ['url' => 'https://www.instagram.com/referendum.social/', 'icon' => 'instagram'],
				'Reddit' => ['url' => 'https://www.reddit.com/r/Surveys_and_statistic/', 'icon' => 'reddit'],
				'X.com' => ['url' => 'https://x.com/O_Statistics', 'icon' => 'x'],
			];
			?>
			<div class="social_grey_b" aria-label="Social media">
				<?php foreach ($socialLinks as $label => $socialLink): ?>
					<?php if ($socialLink['url'] === '') { continue; } ?>
					<?php
					// Використовуємо наявні іконки Font Awesome; у цій версії бібліотеки ще немає X.
					$icon = $socialLink['icon'] === 'x'
						? Html::tag('span', 'X', ['class' => 'social-x-icon', 'aria-hidden' => 'true'])
						: Html::tag('i', '', ['class' => 'fa fa-' . $socialLink['icon'], 'aria-hidden' => 'true']);
					?>
					<?= Html::a(
						$icon,
						$socialLink['url'],
						[
							'class' => 'social-grey-link',
							'title' => $label,
							'aria-label' => $label,
							'target' => '_blank',
							// Не передаємо зовнішньому сервісу доступ до вкладки та referrer.
							'rel' => 'noopener noreferrer',
						]
					) ?>
				<?php endforeach; ?>
			</div>
	        <?php // Блок з повідомленням про помилки на сайті свідомо видалено. ?>
		</div>
</div>
