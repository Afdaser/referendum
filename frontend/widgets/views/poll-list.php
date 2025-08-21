<?php

use yii\widgets\Menu;
use common\models\Poll;

/* @var $this SiteController */
/* @var $search SearchForm */

Yii::$app->params['category'] = $category;
Yii::$app->params['model'] = isset($search) ? $search : null;

$polls = $dataProvider->getModels();
?>

<?= (YII_ENV == 'dev') ? "<!-- #DEV24-04 \n". __FILE__."\n -->" : ''; ?>

<?php if (count($this->context->menu) > 3): ?><div class="my_account_tabs"><?php endif; ?>
<?=
Menu::widget([
    'items' => $this->context->menu,
    'encodeLabels' => false,
    'options' => ['class' => 'nav nav-tabs', 'role' => 'tablist'],
]);
?>
<?php /*
  $this->widget('zii.widgets.CMenu', array(
  'items' => $this->context->menu,
  'encodeLabel' => false,
  'htmlOptions' => array('class' => 'nav nav-tabs', "role" => "tablist"),
  )); /* */
?>
    <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>

<?php if (YII_ENV == 'dev') : ?>
<div style="border:3px dashed green;">
    <h4>Category:<?= $category; ?></h4>
    <p>sort: <?= $sort ?? '-=-'; ?></p>
    <p>limit: <?= $limit ?? '-=-'; ?></p>
    <p>category: <?= $category ?? '-=-'; ?></p>
    <p>tag: <?= $tag ?? '-=-'; ?></p>
    <p>period: <?= $period ?? '-=-'; ?></p>
</div>
<?php endif; ?>

<?php /*
    <pre>
        <?php var_dump($this->context->menu); ?>
    </pre>

 */
/*
 * Yii::$app->user->identity
 * <?= $this->render('/user/registration/registration', array('user'=>empty($user)?$user: Yii::$app->user->getUser(),'error'=>isset($error)?$error:NULL));?>
 *  */
/* */
        ?>
        <?php if (!Yii::$app->user->isGuest): ?>
    <?= $this->render('/user/registration/registration', ['user' => !empty($user) ? $user : Yii::$app->user->identity, 'error' => isset($error) ? $error : NULL]); ?>
        <?php endif; ?>
        <?php if ($category == 'newComments'): ?>
            <div class="tab-content">
                <?php $this->render('/user/_new_comments', array('polls' => $polls)); ?>
            </div>
        <?php elseif ($category == 'newAnswers'): ?>
            <div class="tab-content">
                <?php $this->renderl('/user/_new_answers', array('comments' => $comments)); ?>
            </div>
        <?php elseif ($category != 'profile'): ?>
            <?php if ($category == 'own' && !count($polls)): ?>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= $this->render('polls/_myPollsEmpty'); ?>
                <?php endif; ?>
            <?php elseif ($category != 'actual' || !Yii::$app->user->isGuest): ?>
                <?= $this->render('filters/_sort', array(
                    'category' => $category,
                    'limit' => $limit,
                    'tag' => $tag,
                    'sort' => $sort,
                    'user' => $user,
                    'pollsCount' => $pollsCount,
                    'search' => $search,
                    'period' => $period
                ));
                ?>
                <?= $this->render('polls/_mainPolls', array('polls' => $polls, 'limit' => $limit, 'sort' => $sort)); ?>
                <?php /*
                $this->render('filters/_paginator', array(
                    'limit' => $limit,
                    'sort' => $sort,
                    'pages' => $pages,
                    'tag' => $tag,
                    'user' => $user,
                    'category' => $category,
                    'period' => $period
                )); /* */
                ?>
            <?php endif; ?>
            <?= $this->render('//poll/_newPoll', array('model' => new Poll)); ?>
        <?php else: ?>
            <?= $this->render('/user/profile', array('user' => $user, 'error' => $error, 'profileCategory' => $profileCategory, 'other' => $other)); ?>
<?php endif; ?>


<?php if (!Yii::$app->user->isGuest): ?>
<?php /*
    <script>
    <?php /* Yii1 remove: if (!Yii::$app->user->getUser()->is_active): * / ?>
    <?php if (!Yii::$app->user->identity->isActive): ?>
            $(function () {
                $('#my_profile_main').modal('show');
            });
    <?php elseif (isset($other)): ?>
        <?php if ($other): ?>
                $(function () {
                    $('#my_profile_all').modal('show');
                });
        <?php endif; ?>
    <?php endif; ?>

        $(document).on('click', '.skip_btn', function () {
            $('#my_profile_all').modal('hide');
        });
    });
    </script>
/* */ ?>
<?php
$scriptNotGuest = '';

    if (!Yii::$app->user->identity->isActive) {
        $scriptNotGuest .= <<<JS_ONE
            $(function () {
                $('#my_profile_main').modal('show');
            });
JS_ONE;
    }elseif (isset($other)) {
        if ($other){
        $scriptNotGuest .= <<<JS_TWO
                $(function () {
                    $('#my_profile_all').modal('show');
                });
JS_TWO;
        }
    }
    $scriptNotGuest .= <<<JS_THREE
    $(document).on('click', '.skip_btn', function () {
            $('#my_profile_all').modal('hide');
    });
JS_THREE;
//$script = <<<JS_FINAL
//jQuery(document).ready(function() {
//{$scriptLogin}
//
//});
//JS_FINAL;
//
//$this->registerJs($script, View::POS_END);
$this->registerJs($scriptNotGuest);
?>
<?php endif; ?>

<?php /*
<div class="col-md-8">
        <div class="row right_cut_row">

                                            <div style="border:3px dashed red;">
    <h2>search</h2>
    <h4>/var/www/vhosts_yii/online-statistics.org/online-statistics.org.local/protected/views/site/index.php</h4>
    <pre>    array(0) {
}
    </pre>
</div>
                                                                <form method="POST" action="/site/search/10/desc" name="Search">
        <div class="top_b_chart search_prefix">
            <a class="btn_prev_var go_to_main_btn" href="/site/hotPolls">Main page</a>
            <span class="search_text">Search</span>
        </div>
        <div class="bottom_content_tabs marg_bot">
            <div class="top_input_b item_param item_show">
                <input type="text" class="autocomplete" value="Trump" name="Search[text]" placeholder="Search...">
                <a href="javascript:void(0)" class="search_btn_inner" onclick="document.forms['Search'].submit()">Search</a>
            </div>
            <div class="checkbox_search_block">
                <label>
                    <input type="checkbox" name="Search[search_in_title]" value="1">
                    Search by poll name                </label>
                <label>
                    <input type="checkbox" name="Search[search_in_tags]" checked="" value="1">
                    Search by tags                </label>
            </div>
            <div class="divider_answer for_main_search"></div>
            <div class="select_search_b clearfix">
                <div class="right_reg_label item_param item_show">
                    Country                    <select name="Search[country]" class="country">
                        <option value="0">All</option>
                                                                            <option value="153">США</option>
                                                    <option value="3">Абхазия</option>
                                                    <option value="17">Афганистан</option>
                                                    <option value="7">Албания</option>
                                                    <option value="8">Алжир</option>
                                                    <option value="11">Андорра</option>
                                                    <option value="9">Ангола</option>
                                                    
                                                    <option value="2">Украина</option>
                                                    <option value="193">Уругвай</option>
                                                    <option value="192">Узбекистан</option>
                                                    <option value="38">Вануату</option>
                                                    <option value="41">Венесуэла</option>
                                                    <option value="43">Вьетнам</option>
                                                    <option value="37">Валлис и Футуна о-ва</option>
                                                    <option value="68">Западная Сахара</option>
                                                    <option value="80">Йемен</option>
                                                    <option value="67">Замбия</option>
                                                    <option value="69">Зимбабве</option>
                        &gt;
                    </select>
                </div>
                <div class="right_reg_label item_param item_show" id="region_search_block">
                                        Region                    <input name="Search[region]" type="text" id="regionSearch" style="display: none" value="">
                    <input type="text" class="autocomplete" id="regionACSearch" value="" autocomplete="off">
                    <a href="javascript:void(0)" class="del_btn"></a>
                <div class="autocomplete-suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div></div>
            </div>
            <div class="divider_answer for_main_search for_small_bottom_b"></div>
            <div class="search_form_bottom bottom_select_b_search clearfix" style="top: 0">
                Results found: 3                <span class="right_select_sort">
            Sort:
            <select class="sort">
                <option value="desc" selected="">Rating by decreasing</option>
                <option value="asc">Rating by increasing</option>
            </select>
        </span>
            </div>
        </div>
    </form>

    <script>
        $(function () {
            $(document).on('change', '.country', function () {
                refreshRegions($('.select_search_b .country').val(), $('#region_search_block'), 'regionACSearch', 'regionSearch', 'cityACSearch', $('.city'), 'citySearch');
                $('#regionSearch').val(0);
                $('#regionACSearch').val('');
            });

            refreshRegions($('.select_search_b .country').val(), $('#region_search_block'), 'regionACSearch', 'regionSearch', 'cityACSearch', $('.city'), 'citySearch');

            //getAllRegions($('#region_search_block'), 'regionACSearch', 'regionSearch');

            $(document).on('click','#region_search_block .del_btn',function(){
                $('#regionSearch').val('');
                $('#regionACSearch').val('');
            });

            $(document).on('change','.sort',function(){
                document.forms['Search'].action = "/site/search/"+$(this).val()+"/10";
                document.forms['Search'].submit();
            });

            $(document).on('change','.count_article',function(){
                document.forms['Search'].action = "/site/search/desc/"+$(this).val();
                document.forms['Search'].submit();
            });

            $(document).on('click','.pagination a',function(){
                document.forms['Search'].action = $(this).attr('href');
                document.forms['Search'].submit();
                return false;
            });

            $(function(){
               $('.search_input').val('Trump');
            });
        })
    </script>
                    <div class="bottom_content_tabs">
                    <div class="poll_block">
            <div class="top_poll_b clearfix">
                                    <a href="/tag/USA" class="link_poll">#USA</a>
                                    <a href="/tag/Trump" class="link_poll">#Trump</a>
                                    <a href="/tag/FBI" class="link_poll">#FBI</a>
                
                <span class="right_block_share_icon">
                    <span class="share_icon">
    <i class="fa fa-share-alt"></i>
</span>
<span class="links_share">
    <span class="inner_b_share">
        <a href="javascript:void(0)" class="facebook icon_share" onclick="Share.facebook('http://en.online-statistics.org.local/#P#/poll/382','Do you think that Trump is Traitor? ','http://online-statistic.com/img/layout/logo_social.png','Варіанти відповіді: Yes No I doubt ')">
            <i class="fa fa-facebook"></i>
        </a>
        <a href="javascript:void(0)" class="twitter icon_share" onclick="Share.twitter('http://en.online-statistics.org.local/#P#/poll/382','Do you think that Trump is Traitor? ')">
            <i class="fa fa-twitter"></i>
        </a>
    </span>
</span>                </span>

                            </div>
            <div class="middle_name_poll_b clearfix">
                <div class="left_rating_b">
                    <a href="javascript:void(0)" class="arrow_rating_top" data-id="382"></a><br>
                    <span class="poll_rating" data-id="382">0</span><br>
                    <a href="javascript:void(0)" class="arrow_rating_down" data-id="382"></a>
                </div>
                <div class="middle_title_b">
                    <div class="title_poll">
                        <a href="#P#/poll/382">Do you think that Trump is Traitor? </a>
                    </div>
                    <h3>#DEV1</h3>
                                                                                                </div>
                                    <div class="clearfix"></div>
                    <div class="container_graph">
                            <div class="inner_container_graph pie" id="container382" data-highcharts-chart="0"><div class="highcharts-container" id="highcharts-0" style="position: relative; overflow: hidden; width: 400px; height: 400px; text-align: left; line-height: normal; z-index: 0; left: 0.666672px; top: 0.899994px;"><svg version="1.1" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="400" height="400"><desc>Created with Highcharts 4.0.4</desc><defs><clipPath id="highcharts-1"><rect x="0" y="0" width="380" height="338"></rect></clipPath></defs><rect x="0" y="0" width="400" height="400" strokeWidth="0" fill="#FFFFFF" class=" highcharts-background"></rect><path fill="rgba(5,143,66,0.25)" d="M 0 0"></path><g class="highcharts-series-group" zIndex="3"><g class="highcharts-series highcharts-tracker" visibility="visible" zIndex="0.1" transform="translate(10,10) scale(1 1)" style="cursor:pointer;"><path fill="#e0923e" d="M 188.4766794181769 63.00000237488884 A 114.5 114.5 0 1 1 175.90720518034377 291.30541076165946 L 188.5 177.5 A 0 0 0 1 0 188.5 177.5 Z" stroke="#FFFFFF" stroke-width="1" stroke-linejoin="round" transform="translate(10,1)"></path><path fill="#f5c356" d="M 175.79340608494658 291.292761066238 A 114.5 114.5 0 0 1 131.76737741799212 78.04317753433176 L 188.5 177.5 A 0 0 0 0 0 188.5 177.5 Z" stroke="#FFFFFF" stroke-width="1" stroke-linejoin="round" transform="translate(0,0)"></path><path fill="#058f42" d="M 131.86686259019064 77.98649464961227 A 114.5 114.5 0 0 1 188.3409617970908 63.000110450489885 L 188.5 177.5 A 0 0 0 0 0 188.5 177.5 Z" stroke="#FFFFFF" stroke-width="1" stroke-linejoin="round" transform="translate(0,0)"></path></g><g class="highcharts-markers" visibility="visible" zIndex="0.1" transform="translate(10,10) scale(1 1)"></g></g><g class="highcharts-data-labels highcharts-tracker" visibility="visible" zIndex="6" transform="translate(10,10) scale(1 1)" opacity="1" style="cursor:pointer;"><path fill="none" d="M 337.77799077329416 185.50695812535767 C 332.77799077329416 185.50695812535767 324.7902819415547 185.0636663260299 313.80718229791296 184.45414010195424 L 302.8240826542712 183.84461387787857" stroke="#e0923e" stroke-width="1"></path><path fill="none" d="M 41.975288581602626 206.67200812318794 C 46.975288581602626 206.67200812318794 54.810566722413526 205.05694884993187 65.58407416602856 202.83624234920475 L 76.35758160964359 200.61553584847763" stroke="#f5c356" stroke-width="1"></path><path fill="none" d="M 146.53071863760414 37.80919058310238 C 151.53071863760414 37.80919058310238 153.57746085835961 45.542937817255876 156.3917314118984 56.17684026421694 L 159.2060019654372 66.81074271117801" stroke="#058f42" stroke-width="1"></path><g zIndex="1" style="cursor:pointer;" transform="translate(343,176)"><text x="3" zIndex="1" style="font-size:11px;color:black;fill:black;" y="15"><tspan style="font-weight:bold">51.8%</tspan><tspan x="3" dy="15">44</tspan></text></g><g zIndex="1" style="cursor:pointer;" transform="translate(3,197)"><text x="0" zIndex="1" style="font-size:11px;color:black;fill:black;" y="15"><tspan style="font-weight:bold">40.0%</tspan><tspan x="0" dy="15">34</tspan></text></g><g zIndex="1" style="cursor:pointer;" transform="translate(113,28)"><text x="0" zIndex="1" style="font-size:11px;color:black;fill:black;" y="15"><tspan style="font-weight:bold">8.2%</tspan><tspan x="0" dy="15">7</tspan></text></g></g><g class="highcharts-legend" zIndex="7" transform="translate(106,368)"><g zIndex="1"><g><g class="highcharts-legend-item" zIndex="1" transform="translate(8,3)"><text x="21" style="color:#333333;font-size:12px;font-weight:bold;cursor:pointer;fill:#333333;" text-anchor="start" zIndex="2" y="15">Yes</text><rect x="0" y="4" width="16" height="12" zIndex="3" fill="#e0923e"></rect></g><g class="highcharts-legend-item" zIndex="1" transform="translate(69.6833324432373,3)"><text x="21" y="15" style="color:#333333;font-size:12px;font-weight:bold;cursor:pointer;fill:#333333;" text-anchor="start" zIndex="2">No</text><rect x="0" y="4" width="16" height="12" zIndex="3" fill="#f5c356"></rect></g><g class="highcharts-legend-item" zIndex="1" transform="translate(126.6833324432373,3)"><text x="21" y="15" style="color:#333333;font-size:12px;font-weight:bold;cursor:pointer;fill:#333333;" text-anchor="start" zIndex="2"><tspan>I doubt</tspan></text><rect x="0" y="4" width="16" height="12" zIndex="3" fill="#058f42"></rect></g></g></g></g><g class="highcharts-tooltip" zIndex="8" style="cursor:default;padding:0;white-space:nowrap;" transform="translate(214,-9999)" opacity="0" visibility="visible"><path fill="none" d="M 3.5 0.5 L 47.5 0.5 C 50.5 0.5 50.5 0.5 50.5 3.5 L 50.5 42.5 C 50.5 45.5 50.5 45.5 47.5 45.5 L 3.5 45.5 C 0.5 45.5 0.5 45.5 0.5 42.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" isShadow="true" stroke="black" stroke-opacity="0.049999999999999996" stroke-width="5" transform="translate(1, 1)" width="50" height="45"></path><path fill="none" d="M 3.5 0.5 L 47.5 0.5 C 50.5 0.5 50.5 0.5 50.5 3.5 L 50.5 42.5 C 50.5 45.5 50.5 45.5 47.5 45.5 L 3.5 45.5 C 0.5 45.5 0.5 45.5 0.5 42.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" isShadow="true" stroke="black" stroke-opacity="0.09999999999999999" stroke-width="3" transform="translate(1, 1)" width="50" height="45"></path><path fill="none" d="M 3.5 0.5 L 47.5 0.5 C 50.5 0.5 50.5 0.5 50.5 3.5 L 50.5 42.5 C 50.5 45.5 50.5 45.5 47.5 45.5 L 3.5 45.5 C 0.5 45.5 0.5 45.5 0.5 42.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" isShadow="true" stroke="black" stroke-opacity="0.15" stroke-width="1" transform="translate(1, 1)" width="50" height="45"></path><path fill="rgba(249, 249, 249, .85)" d="M 3.5 0.5 L 47.5 0.5 C 50.5 0.5 50.5 0.5 50.5 3.5 L 50.5 42.5 C 50.5 45.5 50.5 45.5 47.5 45.5 L 3.5 45.5 C 0.5 45.5 0.5 45.5 0.5 42.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" stroke="#e0923e" stroke-width="1"></path><text x="8" zIndex="1" style="font-size:12px;color:#333333;fill:#333333;" y="21"><tspan style="font-size: 10px">Yes</tspan><tspan x="8" dy="16">51.8%</tspan></text></g></svg></div></div>

<script>
	    $(function () {
        renderChart('pie','container382','Do you think that Trump is Traitor? ',[{name: 'Yes',data: [44]},{name: 'No',data: [34]},{name: 'I doubt',data: [7]},],[{name:'Yes',y:44, sliced: true, selected: true },['No',34],['I doubt',7],]);
    });
</script>                    </div>
                            </div>
            <div class="bottom_poll_b clearfix">
                <span class="item_bottom_poll">User: Sebades</span>
                <span class="item_bottom_poll">Comments: 0</span>
                <span class="item_bottom_poll">Voted: 85</span>
                                    <div class="right_poll_status open">
                        Open poll                        <span><i class="fa fa-unlock"></i></span>
                    </div>
                            </div>
        </div>
                    <div class="poll_block">
            <div class="top_poll_b clearfix">
                                    <a href="/tag/USA" class="link_poll">#USA</a>
                                    <a href="/tag/Trump" class="link_poll">#Trump</a>
                                    <a href="/tag/FBI" class="link_poll">#FBI</a>
                                    <a href="/tag/court" class="link_poll">#court</a>
                
                <span class="right_block_share_icon">
                    <span class="share_icon">
    <i class="fa fa-share-alt"></i>
</span>
<span class="links_share">
    <span class="inner_b_share">
        <a href="javascript:void(0)" class="facebook icon_share" onclick="Share.facebook('http://en.online-statistics.org.local/#P#/poll/383','Do you think, that Trump Will go to Prison?','http://online-statistic.com/img/layout/logo_social.png','Варіанти відповіді: Yes No ')">
            <i class="fa fa-facebook"></i>
        </a>
        <a href="javascript:void(0)" class="twitter icon_share" onclick="Share.twitter('http://en.online-statistics.org.local/#P#/poll/383','Do you think, that Trump Will go to Prison?')">
            <i class="fa fa-twitter"></i>
        </a>
    </span>
</span>                </span>

                            </div>
            <div class="middle_name_poll_b clearfix">
                <div class="left_rating_b">
                    <a href="javascript:void(0)" class="arrow_rating_top" data-id="383"></a><br>
                    <span class="poll_rating" data-id="383">0</span><br>
                    <a href="javascript:void(0)" class="arrow_rating_down" data-id="383"></a>
                </div>
                <div class="middle_title_b">
                    <div class="title_poll">
                        <a href="#P#/poll/383">Do you think, that Trump Will go to Prison?</a>
                    </div>
                    <h3>#DEV1</h3>
                                                                                                        <div class="inner_block_chosen">
                                        <div class="item_chose_poll">
            <a href="/poll/vote?option=1512" class="radio_link poll-option-vote"><span class="radio_circle"></span>
                <span class="link_text">Yes</span>
            </a>
        </div>
            <div class="item_chose_poll">
            <a href="/poll/vote?option=1513" class="radio_link poll-option-vote"><span class="radio_circle"></span>
                <span class="link_text">No</span>
            </a>
        </div>
    
<script>
	    $(function () {
        renderChart('bar','container383','Do you think, that Trump Will go to Prison?',[{name: 'Yes',data: [20]},{name: 'No',data: [20]},],[{name:'Yes',y:20, sliced: true, selected: true },['No',20],]);
    });
</script>                        </div>
                                    </div>
                            </div>
            <div class="bottom_poll_b clearfix">
                <span class="item_bottom_poll">User: Sebades</span>
                <span class="item_bottom_poll">Comments: 0</span>
                <span class="item_bottom_poll">Voted: 40</span>
                                    <div class="right_poll_status open">
                        Open poll                        <span><i class="fa fa-unlock"></i></span>
                    </div>
                            </div>
        </div>
                        <div class="inner_banner_b">
                <img src="/img/banner_inner_blocks.png" alt="">
            </div>
                <div class="poll_block">
            <div class="top_poll_b clearfix">
                                    <a href="/tag/USA" class="link_poll">#USA</a>
                                    <a href="/tag/Trump" class="link_poll">#Trump</a>
                                    <a href="/tag/Politics" class="link_poll">#Politics</a>
                                    <a href="/tag/court" class="link_poll">#court</a>
                
                <span class="right_block_share_icon">
                    <span class="share_icon">
    <i class="fa fa-share-alt"></i>
</span>
<span class="links_share">
    <span class="inner_b_share">
        <a href="javascript:void(0)" class="facebook icon_share" onclick="Share.facebook('http://en.online-statistics.org.local/#P#/poll/401','Do you stand with Steve Bannon? ','http://online-statistic.com/img/layout/logo_social.png','Варіанти відповіді: Yes No ')">
            <i class="fa fa-facebook"></i>
        </a>
        <a href="javascript:void(0)" class="twitter icon_share" onclick="Share.twitter('http://en.online-statistics.org.local/#P#/poll/401','Do you stand with Steve Bannon? ')">
            <i class="fa fa-twitter"></i>
        </a>
    </span>
</span>                </span>

                            </div>
            <div class="middle_name_poll_b clearfix">
                <div class="left_rating_b">
                    <a href="javascript:void(0)" class="arrow_rating_top" data-id="401"></a><br>
                    <span class="poll_rating" data-id="401">0</span><br>
                    <a href="javascript:void(0)" class="arrow_rating_down" data-id="401"></a>
                </div>
                <div class="middle_title_b">
                    <div class="title_poll">
                        <a href="#P#/poll/401">Do you stand with Steve Bannon? </a>
                    </div>
                    <h3>#DEV1</h3>
                                                                                                        <div class="inner_block_chosen">
                                        <div class="item_chose_poll">
            <a href="/poll/vote?option=1554" class="radio_link poll-option-vote"><span class="radio_circle"></span>
                <span class="link_text">Yes</span>
            </a>
        </div>
            <div class="item_chose_poll">
            <a href="/poll/vote?option=1555" class="radio_link poll-option-vote"><span class="radio_circle"></span>
                <span class="link_text">No</span>
            </a>
        </div>
    
<script>
	    $(function () {
        renderChart('pie','container401','Do you stand with Steve Bannon? ',[{name: 'Yes',data: [6]},{name: 'No',data: [7]},],[['Yes',6],{name:'No',y:7, sliced: true, selected: true },]);
    });
</script>                        </div>
                                    </div>
                            </div>
            <div class="bottom_poll_b clearfix">
                <span class="item_bottom_poll">User: Sebades</span>
                <span class="item_bottom_poll">Comments: 0</span>
                <span class="item_bottom_poll">Voted: 13</span>
                                    <div class="right_poll_status open">
                        Open poll                        <span><i class="fa fa-unlock"></i></span>
                    </div>
                            </div>
        </div>
    </div>

                                <div class="bottom_pagination_b clearfix">
    
    <div class="right_count_select">
        Polls on the page:
                    <select class="count_article">
                <option value="10" selected="">10</option>
                <option value="5">5</option>
                <option value="2">2</option>
            </select>
            </div>
</div>                                <!-- Modal -->
<div class="modal new_poll" id="new_poll0" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">New polls</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="/poll/createPoll">
                    <input name="Poll[id]" type="hidden" value="">

                    <div class="title_modal">Question</div>
                    <div class="input_text_modal_b middle_text_input_b">
                        <textarea name="Poll[title]" id="title"></textarea>
                        <div class="count_symbols">
                            left: 50 Symbols                        </div>
                    </div>
                    <div class="title_modal">Description <span>(Not necessarily)</span></div>
                    <div class="input_text_modal_b middle_text_input_b">
                        <textarea name="Poll[describe]"></textarea>
                    </div>

                    <div class="title_modal">Answer variants</div>
                                            <div class="item_variants">
                        <span>1</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            left: <div class="answer_left">60</div> Symbols                        </div>
                        <a href="#" class="del_btn" data-id="0"></a>
                    </div>
                    <div class="item_variants">
                        <span>2</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            left: <div class="answer_left">60</div> Symbols                        </div>
                        <a href="#" class="del_btn" data-id="0"></a>
                    </div>
                    <div class="item_variants">
                        <span>3</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            left: <div class="answer_left">60</div> Symbols                        </div>
                        <a href="#" class="del_btn" data-id="0"></a>
                    </div>
                    <div class="item_variants">
                        <span>4</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            left: <div class="answer_left">60</div> Symbols                        </div>
                        <a href="#" class="del_btn" data-id="0"></a>
                    </div>
                                            <a href="#" class="create_new_poll my_profile modal_add" data-id="0">Add variant</a>
                </form></div>
                <div class="modal-body">
                    <div class="title_modal">Tags</div>
                    <div class="sub_title_modal">Enter tags to poll separated by a comma.</div>
                    <div class="input_text_modal_b middle_text_input_b">
                        <textarea name="Poll[tags]"></textarea>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="title_modal">Show results</div>
                    <div class="sub_title_modal">Select the type of graph to display the results of voting:</div>
                    <div class="white_b_graphs">
                        <div class="tabs_graphs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active"><input id="type_1" type="radio" name="Poll[type]" value="1" checked="" hidden=""><a href="#horizontal_b_chart" class="horizontal_b_chart" role="tab" data-toggle="tab"></a></li>
                                <li class=""><input id="type_2" type="radio" name="Poll[type]" value="2" hidden=""><a href="#vertical_b_chart" class="vertical_b_chart" role="tab" data-toggle="tab"></a></li>
                                <li class=""><input id="type_3" type="radio" name="Poll[type]" value="3" hidden=""><a href="#pie_chart" class="pie_chart" role="tab" data-toggle="tab"></a></li>
                            </ul>
                        </div>
                        <div class="tab-content tab_visual">
                            <div id="horizontal_b_chart" class="tab-pane active" style="width:500px; height:400px;"></div>
                            <div id="vertical_b_chart" class="tab-pane " style="width:500px; height:400px;"></div>
                            <div id="pie_chart" class="tab-pane " style="width:500px; height:400px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="my_profile modal_add next_modal_btn" data-dismiss="modal" data-target="#new_poll_next_step0" data-toggle="modal">NEXT</a>
                    <div><a href="#" class="create_new_poll newPollCancel" data-dismiss="modal">Cancel</a></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal new_poll poll" id="new_poll_next_step0" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title">New polls</h4>
                </div>
                <div class="modal-body">
                    <div class="title_modal">Type of poll</div>
                    <div class="sub_title_modal">- Open voting - users are immediately available poll results;</div>
                    <div class="sub_title_modal">- Closed voting - results are available after the vote</div>
                    <div class="radio_b_chose">
                        <input id="vote_1" type="radio" name="Poll[status]" value="0" checked="">
                        <label for="vote_1" class="radio_open_vote">
                            Open poll                            <i class="fa fa-unlock"></i>
                        </label>
                        <input id="vote_2" type="radio" name="Poll[status]" value="1">
                        <label for="vote_2" class="radio_close_vote">
                            Closed poll                            <i class="fa fa-lock"></i>
                        </label>
                    </div>
                    <div class="for_close_radio clearfix" style="display: none;">
                        Closed until the number of votes::
                        <input name="Poll[votes_count_close]" type="text" value="" placeholder="0">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="title_modal">Show poll option</div>
                    <div class="sub_title_modal">Poll display settings for user groups by language, age, region, etc..</div>
                    <div class="item_param item_show clearfix">
                            <span class="left_label_text">
                                Language poll                            </span>
                            <span class="right_select_b">
                                <select name="Poll[language]" class="lang_select">
                                                                                                                <option value="1">Українська</option>
                                                                            <option value="2">Русский</option>
                                                                            <option value="3">English</option>
                                                                            <option value="4">Norsk</option>
                                                                    </select>
                                <!--label class="for_all_check">
                                    <input name="Poll[all_language]" type="checkbox" value="1" >
                                    Show for all languages                                </label-->
                            </span>
                    </div>
                    <div class="item_param item_show clearfix">
                            <span class="left_label_text">
                                Male                            </span>
                            <span class="right_select_b">
                                <select name="Poll[sex]">
                                    <option value="0" selected="">All</option>
                                                                                                                <option value="1">Male</option>
                                                                            <option value="2">Female</option>
                                                                    </select>
                            </span>
                    </div>
                                        <div class="item_param item_show clearfix ages">
                        <span class="left_label_text">
                           Age                        </span>
                        <span class="right_select_b clearfix">
                            <span class="for_ages_left clearfix">
                                <span class="small_text">From</span>
                                <select name="Poll[min_age]">
                                    <option value="0" selected="">All</option>
                                                                            <option value="10">10</option>
                                                                            <option value="15">15</option>
                                                                            <option value="25">25</option>
                                                                            <option value="60">60</option>
                                                                    </select>
                            </span>
                            <span class="for_ages clearfix">
                                <span class="small_text pull_left_pad">to</span>
                                <select name="Poll[max_age]">
                                    <option value="0" selected="">All</option>
                                                                            <option value="10">10</option>
                                                                            <option value="15">15</option>
                                                                            <option value="25">25</option>
                                                                            <option value="60">60</option>
                                                                    </select>
                            </span>
                        </span>
                    </div>
                    <div class="item_param item_show clearfix">
                            <span class="left_label_text">
                                Country                            </span>
                            <span class="right_select_b">
                                <select name="Poll[country]" class="country">
                                    <option value="0" selected="">All</option>
                                                                                                                <option value="153">США</option>
                                                                            <option value="3">Абхазия</option>
                                                                            <option value="17">Афганистан</option>
                                                                            <option value="7">Албания</option>
                                                                            <option value="8">Алжир</option>
                                                                            <option value="11">Андорра</option>
                                                                            <option value="9">Ангола</option>
                                                                            <option value="10">Ангуилья</option>
                                                                            <option value="12">Антигуа и Барбуда</option>
                                                                            <option value="13">Антильские о-ва</option>
                                                                            <option value="14">Аргентина</option>
                                                                            <option value="15">Армения</option>
                                                                            <option value="16">Арулько</option>
                                                                            <option value="4">Австралия</option>
                                                                            <option value="5">Австрия</option>
                                                                            <option value="6">Азербайджан</option>
                                                                            <option value="18">Багамские о-ва</option>
                                                                            <option value="21">Бахрейн</option>
                                                                            <option value="19">Бангладеш</option>
                                                                            <option value="20">Барбадос</option>
                                                                            <option value="22">Беларусь</option>
                                                                            <option value="24">Бельгия</option>
                                                                            <option value="23">Белиз</option>
                                                                            <option value="25">Бенин</option>
                                                                            <option value="26">Бермуды</option>
                                                                            <option value="28">Боливия</option>
                                                                            <option value="29">Босния/Герцеговина</option>
                                                                            <option value="30">Ботсвана</option>
                                                                            <option value="31">Бразилия</option>
                                                                            <option value="32">Британские Виргинские о-ва</option>
                                                                            <option value="33">Бруней</option>
                                                                            <option value="27">Болгария</option>
                                                                            <option value="34">Буркина Фасо</option>
                                                                            <option value="35">Бурунди</option>
                                                                            <option value="36">Бутан</option>
                                                                            <option value="83">Камбоджа</option>
                                                                            <option value="84">Камерун</option>
                                                                            <option value="85">Канада</option>
                                                                            <option value="81">Кабо-Верде</option>
                                                                            <option value="202">Чад</option>
                                                                            <option value="205">Чили</option>
                                                                            <option value="90">Китай</option>
                                                                            <option value="91">Колумбия</option>
                                                                            <option value="92">Коморские о-ва</option>
                                                                            <option value="93">Конго (Brazzaville)</option>
                                                                            <option value="94">Конго (Kinshasa)</option>
                                                                            <option value="99">Кука о-ва</option>
                                                                            <option value="95">Коста-Рика</option>
                                                                            <option value="201">Хорватия</option>
                                                                            <option value="97">Куба</option>
                                                                            <option value="88">Кипр</option>
                                                                            <option value="204">Чехия</option>
                                                                            <option value="62">Дания</option>
                                                                            <option value="64">Джибути</option>
                                                                            <option value="65">Доминиканская республика</option>
                                                                            <option value="42">Восточный Тимор</option>
                                                                            <option value="209">Эквадор</option>
                                                                            <option value="66">Египет</option>
                                                                            <option value="210">Экваториальная Гвинея</option>
                                                                            <option value="211">Эритрея</option>
                                                                            <option value="212">Эстония</option>
                                                                            <option value="213">Эфиопия</option>
                                                                            <option value="194">Фарерские о-ва</option>
                                                                            <option value="195">Фиджи</option>
                                                                            <option value="197">Финляндия</option>
                                                                            <option value="198">Франция</option>
                                                                            <option value="199">Французская Гвинея</option>
                                                                            <option value="200">Французская Полинезия</option>
                                                                            <option value="44">Габон</option>
                                                                            <option value="47">Гамбия</option>
                                                                            <option value="61">Грузия</option>
                                                                            <option value="53">Германия</option>
                                                                            <option value="48">Гана</option>
                                                                            <option value="55">Гибралтар</option>
                                                                            <option value="39">Великобритания</option>
                                                                            <option value="60">Греция</option>
                                                                            <option value="59">Гренландия</option>
                                                                            <option value="58">Гренада</option>
                                                                            <option value="49">Гваделупа</option>
                                                                            <option value="50">Гватемала</option>
                                                                            <option value="54">Гернси о-в</option>
                                                                            <option value="51">Гвинея</option>
                                                                            <option value="52">Гвинея-Бисау</option>
                                                                            <option value="46">Гайана</option>
                                                                            <option value="45">Гаити</option>
                                                                            <option value="56">Гондурас</option>
                                                                            <option value="57">Гонконг</option>
                                                                            <option value="40">Венгрия</option>
                                                                            <option value="77">Исландия</option>
                                                                            <option value="71">Индия</option>
                                                                            <option value="72">Индонезия</option>
                                                                            <option value="75">Иран</option>
                                                                            <option value="74">Ирак</option>
                                                                            <option value="76">Ирландия</option>
                                                                            <option value="127">Мэн о-в</option>
                                                                            <option value="70">Израиль</option>
                                                                            <option value="79">Италия</option>
                                                                            <option value="96">Кот-д'Ивуар</option>
                                                                            <option value="217">Ямайка</option>
                                                                            <option value="218">Япония</option>
                                                                            <option value="63">Джерси о-в</option>
                                                                            <option value="73">Иордания</option>
                                                                            <option value="82">Казахстан</option>
                                                                            <option value="87">Кения</option>
                                                                            <option value="89">Кирибати</option>
                                                                            <option value="98">Кувейт</option>
                                                                            <option value="100">Кыргызстан</option>
                                                                            <option value="101">Лаос</option>
                                                                            <option value="102">Латвия</option>
                                                                            <option value="105">Ливан</option>
                                                                            <option value="103">Лесото</option>
                                                                            <option value="104">Либерия</option>
                                                                            <option value="106">Ливия</option>
                                                                            <option value="108">Лихтенштейн</option>
                                                                            <option value="107">Литва</option>
                                                                            <option value="109">Люксембург</option>
                                                                            <option value="113">Македония</option>
                                                                            <option value="112">Мадагаскар</option>
                                                                            <option value="114">Малави</option>
                                                                            <option value="115">Малайзия</option>
                                                                            <option value="117">Мальдивские о-ва</option>
                                                                            <option value="116">Мали</option>
                                                                            <option value="118">Мальта</option>
                                                                            <option value="119">Мартиника о-в</option>
                                                                            <option value="111">Мавритания</option>
                                                                            <option value="110">Маврикий</option>
                                                                            <option value="120">Мексика</option>
                                                                            <option value="122">Молдова</option>
                                                                            <option value="123">Монако</option>
                                                                            <option value="124">Монголия</option>
                                                                            <option value="203">Черногория</option>
                                                                            <option value="125">Марокко</option>
                                                                            <option value="121">Мозамбик</option>
                                                                            <option value="126">Мьянма (Бирма)</option>
                                                                            <option value="128">Намибия</option>
                                                                            <option value="129">Науру</option>
                                                                            <option value="130">Непал</option>
                                                                            <option value="133">Нидерланды (Голландия)</option>
                                                                            <option value="136">Новая Каледония о-в</option>
                                                                            <option value="135">Новая Зеландия</option>
                                                                            <option value="134">Никарагуа</option>
                                                                            <option value="131">Нигер</option>
                                                                            <option value="132">Нигерия</option>
                                                                            <option value="138">Норфолк о-в</option>
                                                                            <option value="162">Северная Корея</option>
                                                                            <option value="137">Норвегия</option>
                                                                            <option value="139">О.А.Э.</option>
                                                                            <option value="140">Оман</option>
                                                                            <option value="141">Пакистан</option>
                                                                            <option value="142">Панама</option>
                                                                            <option value="143">Папуа Новая Гвинея</option>
                                                                            <option value="144">Парагвай</option>
                                                                            <option value="145">Перу</option>
                                                                            <option value="196">Филиппины</option>
                                                                            <option value="146">Питкэрн о-в</option>
                                                                            <option value="147">Польша</option>
                                                                            <option value="148">Португалия</option>
                                                                            <option value="149">Пуэрто Рико</option>
                                                                            <option value="86">Катар</option>
                                                                            <option value="150">Реюньон</option>
                                                                            <option value="152">Румыния</option>
                                                                            <option value="1">Россия</option>
                                                                            <option value="151">Руанда</option>
                                                                            <option value="161">Святой Елены о-в</option>
                                                                            <option value="166">Сент Китс и Невис</option>
                                                                            <option value="160">Святая Люсия</option>
                                                                            <option value="164">Сен-Пьер и Микелон</option>
                                                                            <option value="167">Сент-Винсент и Гренадины</option>
                                                                            <option value="154">Сальвадор</option>
                                                                            <option value="155">Самоа</option>
                                                                            <option value="156">Сан-Марино</option>
                                                                            <option value="157">Сан-Томе и Принсипи</option>
                                                                            <option value="158">Саудовская Аравия</option>
                                                                            <option value="165">Сенегал</option>
                                                                            <option value="168">Сербия</option>
                                                                            <option value="163">Сейшеллы</option>
                                                                            <option value="177">Сьерра-Леоне</option>
                                                                            <option value="169">Сингапур</option>
                                                                            <option value="171">Словакия</option>
                                                                            <option value="172">Словения</option>
                                                                            <option value="173">Соломоновы о-ва</option>
                                                                            <option value="174">Сомали</option>
                                                                            <option value="214">ЮАР</option>
                                                                            <option value="215">Южная Корея</option>
                                                                            <option value="216">Южная Осетия</option>
                                                                            <option value="78">Испания</option>
                                                                            <option value="208">Шри-Ланка</option>
                                                                            <option value="175">Судан</option>
                                                                            <option value="176">Суринам</option>
                                                                            <option value="159">Свазиленд</option>
                                                                            <option value="207">Швеция</option>
                                                                            <option value="206">Швейцария</option>
                                                                            <option value="170">Сирия</option>
                                                                            <option value="179">Тайвань</option>
                                                                            <option value="178">Таджикистан</option>
                                                                            <option value="181">Танзания</option>
                                                                            <option value="180">Таиланд</option>
                                                                            <option value="182">Того</option>
                                                                            <option value="183">Токелау о-ва</option>
                                                                            <option value="184">Тонга</option>
                                                                            <option value="185">Тринидад и Тобаго</option>
                                                                            <option value="187">Тунис</option>
                                                                            <option value="190">Турция</option>
                                                                            <option value="188">Туркменистан</option>
                                                                            <option value="189">Туркс и Кейкос</option>
                                                                            <option value="186">Тувалу</option>
                                                                            <option value="191">Уганда</option>
                                                                            <option value="2">Украина</option>
                                                                            <option value="193">Уругвай</option>
                                                                            <option value="192">Узбекистан</option>
                                                                            <option value="38">Вануату</option>
                                                                            <option value="41">Венесуэла</option>
                                                                            <option value="43">Вьетнам</option>
                                                                            <option value="37">Валлис и Футуна о-ва</option>
                                                                            <option value="68">Западная Сахара</option>
                                                                            <option value="80">Йемен</option>
                                                                            <option value="67">Замбия</option>
                                                                            <option value="69">Зимбабве</option>
                                    &gt;
                                </select>
                            </span>
                    </div>
                    <div class="item_param item_show clearfix region">
                            <span class="left_label_text">
                                Region                            </span>
                            <span class="right_select_b" id="appended_b">
                                <input name="Poll[region]" type="text" id="regionPoll" style="display: none" value="">
                                <input type="text" class="autocomplete" id="regionACPoll" value="" autocomplete="off">
                                <a href="#" class="del_btn" data-id="new_poll_next_step0"></a>
                            <div class="autocomplete-suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div></span>
                    </div>
                    <div class="item_param item_show clearfix city">
                            <span class="left_label_text">
                                Location                            </span>
                            <span class="right_select_b" id="appended_b">
                                <input name="Poll[city]" type="text" id="cityPoll" style="display: none" value="">
                                <input type="text" class="autocomplete" id="cityACPoll" value="">
                                <a href="#" class="del_btn" data-id="new_poll_next_step0"></a>
                            </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="sub_title_modal"></div>
                    <div class="btn_b_modal">
                        <a href="#" class="my_profile modal_add next_modal_btn back_btn_modal" data-dismiss="modal" data-toggle="modal" data-target="#new_poll0">BACK</a>

                        <button type="submit" class="my_profile modal_add next_modal_btn">CREATE</button>
                    
                </div>
                <a href="#" class="create_new_poll newPollCancel" data-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $(document).on('change','#new_poll_next_step0 .country',function(){ /// file: ~/frontend/widgets/views/poll-list.php
            $('#new_poll_next_step0 #regionACPoll').val('');
            $('#new_poll_next_step0 #regionPoll').val(0);
            $('#new_poll_next_step0 #cityACPoll').val('');
            $('#new_poll_next_step0 #cityPoll').val(0);
            refreshRegions($('#new_poll_next_step0 .country').val(),$('#new_poll_next_step0 div.region .right_select_b'),'new_poll_next_step0 #regionACPoll','new_poll_next_step0 #regionPoll','new_poll_next_step0 #cityACPoll',$('#new_poll_next_step0 .city  .right_select_b'),'new_poll_next_step0 #cityPoll');
        });

        $(document).on('change','#new_poll_next_step0 #regionACPoll',function(){
            $('#new_poll_next_step0 #cityACPoll').val('');
            $('#new_poll_next_step0 #cityPoll').val(0);
        });

        refreshRegions($('#new_poll_next_step0 .country').val(),$('#new_poll_next_step0 div.region .right_select_b'),'new_poll_next_step0 #regionACPoll','new_poll_next_step0 #regionPoll','new_poll_next_step0 #cityACPoll',$('#new_poll_next_step0 .city .right_select_b'),'new_poll_next_step0 #cityPoll');

        $(document).on('change','#new_poll0 #title, #new_poll0  .variant_text',function(){
           refreshChart('0');
        });

        $(document).on('click','#new_poll0 .del_btn',function(){
            setTimeout(refreshChartWithTimeout, 1000)
        });

        function refreshChartWithTimeout(){
            refreshChart('0');
        }

                    $('#new_poll_next_step0 .for_close_radio').hide();
        
            })
</script> 
        </div>
    </div>
<?php


/*
<div style="border:1px dashed red;">
    <?php
    echo '<h2>'.__FILE__.'</h2>';
    ?>
  <?php    foreach ($dataProvider->models as $key => $poll) :?> 
    <?php echo '<h4>$key:['.$key.']</h4>'; ?>
        <?= $this->render('poll-list-item', ['model' => $poll]); ?>
    <?php endforeach; ?>
</div>
/* */
?>

<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV24-04 -->'; ?>