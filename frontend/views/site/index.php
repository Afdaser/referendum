<?php
/** @var yii\web\View $this */
$this->title = 'My Yii Application';
?>
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
<?php else: ?>
<ul class="nav nav-tabs" role="tablist" id="yw0">
<li><a href="/">Hot topics</a></li>
<li><a href="/site/actualPolls">Topics relevant to you</a></li>
<li><a href="/site/myPolls">Your polls</a></li>
</ul>
<hr>
<h2 style="border:2px dashed red;">count($this->menu)>3:</h2>
<hr>

        <?php endif; ?>
        <div class="chart_b user_page_b">
            <div class="top_b_chart">
                <a class="btn_prev_var"
                   href="<?php echo Yii::$app->request->referrer; ?>"><?php echo Yii::t("poll", 'Назад'); ?></a>
            </div>
            <div class="my_profile_b">
                <div class="profile_name">
                    <h2>Lang: <?= Yii::$app->language; ?></h2>
                </div>                    
                <div class="profile_name">
                    <h1 class="display-4">Congratulations!+</h1>
                </div>
                <div class="body-content">

                    <div class="row">
                        <div class="col-md-6">
                            <h2>Heading</h2>

                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                fugiat nulla pariatur.</p>

                            <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
                        </div>
                        <div class="col-md-6">
                            <h2>Heading</h2>

                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                fugiat nulla pariatur.</p>

                            <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php /*
  <div class="site-index">
  <div class="p-5 mb-4 bg-transparent rounded-3">
  <div class="container-fluid py-5 text-center">

  <p class="fs-5 fw-light">You have successfully created your Yii-powered application.</p>
  <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
  </div>
  </div>


  </div>
  /* */ ?>