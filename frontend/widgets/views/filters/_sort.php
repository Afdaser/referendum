<?php
// Yii 2
// /var/www/vhosts_yii/referendum.social/referendum.social.local/frontend/widgets/views/filters/_sort.php

use yii\helpers\Url;

use common\models\Country;
use common\models\CountryRegion;
use common\models\User;

// #TODO  $user->commentsCount
/*
 * <?= (YII_ENV != 'dev') ? '' : "<h4> ! -- #DEV24-03 \n". __FILE__."\n -- </h4>"; ?>
 */

?>
<?php if (YII_ENV == 'dev') : ?>
<div style="border:3px dotted blueviolet; padding:5px;">
    <?php if (isset($user)) : ?>
    <div style="border:3px dotted blueviolet; padding:5px;">
            <pre>
                <?php // var_dump($user); ?>
                <?php debug_print_backtrace(); ?>
            </pre>
        </div>
    <?php endif; ?>

<?php endif; ?>
<?= (YII_ENV != 'dev') ? '' : "<h4> ! -- #DEV24-03 \n". __FILE__."\n -- </h4>"; ?>

<?php /* #DEV01: */ ?>
<?php if (YII_DEBUG || !empty($debug)) :?>
<div style="border:2px dashed red;">
    <h4><?= '#DEV01:category: ['.$category.']';?></h4>
    <p><?= str_replace(Yii::getAlias('@app') , '~', __FILE__) ; ?></p>
    <p>User:[<?= $user->id ?? '-=-' ; ?>] isEmpty: <?= intval(empty($user)); ?> </p>
</div>
<?php endif; ?>
<?php /* //#DEV01: */ ?>

<?php if ($user): ?>

<?php if (YII_DEBUG || !empty($debug)) :?>
<div style="border:2px dashed red;"><?= '#DEV03:block01 [if ($user)]'; ?></div>
<?php endif; ?>

    <div class="chart_b user_page_b">
        <div class="top_b_chart">
            <a class="btn_prev_var"
               href="<?=  Yii::$app->request->referrer; ?>"><?= Yii::t("poll", 'Назад'); ?><?= (YII_ENV == 'dev') ? '#DEV24-03' : ''; ?></a>
        </div>
        <div class="my_profile_b">
            <div class="profile_name">
                <?= $user->getFullUserName(); ?>
            </div>
            <div class="text_my_profile_data">
                <?= Yii::t("poll", 'Рейтинг користувача'); ?>:  <?= User::getUserRating($user->id);?><br>
                <?= Yii::t("poll", 'Всього опитувань'); ?>: <?= User::getPollsCount($user->id); ?><br>
                <?= Yii::t("poll", 'Всього коментарів'); ?>: <?= $user->commentsCount; ?>
            </div>
        </div>
        <div class="sort_b clearfix">
            <span class="left_btn_b">
                <?= Yii::t("poll", 'Опитування користувача'); ?>
            </span>
           <span class="right_select_sort">
            <?= Yii::t("filter", 'Сортування'); ?>:
            <select class="sort"
                    onchange='document.location.href = "<?= Url::toRoute('/site') . '/' .  Yii::$app->controller->action->id; ?>" + "/" + "<?php echo $user->id; ?>" + "/" + $(this).val() + "/" + "<?php echo $limit; ?>"'>
                <option value="desc"
                        <?php if ($sort == 'desc'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'Рейтинг по спаданню'); ?></option>
                <option value="asc"
                        <?php if ($sort == 'asc'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'Рейтинг по зростанню'); ?></option>
            </select>
        </span>
        </div>
    </div>
<?php elseif ($category == 'search'): ?>

    <?php if (empty($sort)) :
        $sort = 'desc';
     endif; ?>
    <?php if (YII_DEBUG || !empty($debug)) :?>
    <div style="border:2px dashed red;"><?= '#DEV03:block02 [category == search]'; ?></div>
    <?php endif; ?>
<?php /* * / ?>
    <FORM METHOD="POST" ACTION="/site/search/<?php echo $limit?>/<?php echo $sort?>" name="Search">
<?php /* */ ?>
    <form method="post" action="<?= Url::toRoute(['/poll/search/search', 'limit' => $limit, 'sorting' => $sort]); ?>" name="SearchForm">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

        <div class="top_b_chart search_prefix">
            <a class="btn_prev_var go_to_main_btn" href="<?= Url::base(true) ?>"><?= Yii::t("filter", 'Головна'); ?></a>
            <span class="search_text"><?= Yii::t("filter", 'Пошук'); ?></span>
        </div>
        <div class="bottom_content_tabs marg_bot">
            <div class="top_input_b item_param item_show">
                <input type="text" class="autocomplete" value="<?php echo $search->text ?>" name="SearchForm[text]"
                       placeholder="<?= Yii::t("filter", 'Пошук'); ?>...">
                <a href="javascript:void(0)" class="search_btn_inner" onclick="document.forms['SearchForm'].submit()"><?= Yii::t("filter", 'Пошук'); ?></a>
            </div>
            <div class="checkbox_search_block">
                <label>
                    <input type="checkbox" name="SearchForm[search_in_title]" <?php if($search->searchInTitle): ?>checked<?php endif;?> value="1">
                    <?= Yii::t("filter", 'Пошук по назві опитування'); ?>
                </label>
                <label>
                    <input type="checkbox" name="SearchForm[search_in_tags]" <?php if($search->searchInTags): ?>checked<?php endif;?> value="1">
                    <?= Yii::t("filter", 'Пошук по тегах'); ?>
                </label>
            </div>
            <div class="search_form_bottom bottom_select_b_search clearfix" style="top: 0">
                <?= Yii::t("filter", 'Знайдено результатів'); ?>: <?= $pollsCount; ?>
                <span class="right_select_sort">
                    <?= Yii::t("filter", 'Сортування'); ?>:
                    <select class="sort">
                        <option value="desc" <?= ($sort == 'desc') ? 'selected' : '' ; ?>><?= Yii::t("filter", 'Рейтинг по спаданню'); ?></option>
                        <option value="asc" <?= ($sort == 'asc') ? 'selected' : '' ; ?>><?= Yii::t("filter", 'Рейтинг по зростанню'); ?></option>
                    </select>
                </span>
            </div>
        </div>
    </FORM>
<?php /* * / ?>
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
                document.forms['SearchForm'].action = "/site/search/"+$(this).val()+"/<?php echo $limit?>";
                document.forms['SearchForm'].submit();
            });

            $(document).on('change','.count_article',function(){
                document.forms['SearchForm'].action = "/site/search/<?php echo $sort?>/"+$(this).val();
                document.forms['SearchForm'].submit();
            });

            $(document).on('click','.pagination a',function(){
                document.forms['SearchForm'].action = $(this).attr('href');
                document.forms['SearchForm'].submit();
                return false;
            });

            $(function(){
               $('.search_input').val('<?php echo $search->text ?>');
            });
        })
    </script>
<?php /* */ ?>
<?php elseif ($category != 'profile'): ?>

<?php if (YII_DEBUG || !empty($debug)) :?>
<div style="border:2px dashed red;"><?= '#DEV03:block03a [category == != profile]'; ?></div>
<?php endif; ?>
        <?php
        if  (Yii::$app->controller->action->id == 'index' && $category =='hot'){
            $route = '/poll/site/hot-polls';
            $uriPrefix = Url::toRoute([$route]);
            $uriPrefix = '/site/hotPolls';
        }else{
            $route = '/poll/site/' . Yii::$app->controller->action->id;
            $uriPrefix = Url::toRoute([$route]);

        }
        ?>
    <div class="sort_b">
        <?php if ($category == 'own'): ?>
            <span class="left_btn_b">
<?php /* New Error ajax loading modal* / ?>
                <a class="create_new_poll my_profile" id="btn_create_new_poll"
<?php /* */ ?>
                <a data-target="#new_poll0" data-toggle="modal" class="create_new_poll my_profile"
<?php /* */ ?>
                   href="#"><?= Yii::t("filter", 'Cтворити нове'); ?></a>
            </span>
        <?php else:?>
            <span class="right_select_sort left_option_period">

<?php /* OLD:
                <select
                    onchange='document.location.href = "<?= Url::toRoute('/site') . '/' .  Yii::$app->controller->action->id; ?>/<?php echo $sort;?>" + "/" + $(this).val() + "/" + "<?php echo $limit; ?>" + "?click=true"'>
 *
 */ ?>
                <select
                    onchange='document.location.href = "<?= "{$uriPrefix}/{$sort}/"; ?>" + $(this).val() + "<?= "/{$limit}?click=true"; ?>"'>
                    <option value="day"
                            <?php if ($period == 'day'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за день'); ?></option>
                    <option value="week"
                            <?php if ($period == 'week'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за неділю'); ?></option>
                    <option value="month"
                            <?php if ($period == 'month'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за місяць'); ?></option>
                    <option value="year"
                            <?php if ($period == 'year'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за рік'); ?></option>
                </select>
            </span>
        <?php endif; ?>
        <span class="right_select_sort">
<?php /* OLD:
            <select class="sort"
                   onchange='document.location.href = "<?= Url::toRoute('/site') . '/' .  Yii::$app->controller->action->id; ?>" + "/" + $(this).val() + "/" + "<?php echo $period; ?>/<?php echo $limit; ?>"'>
 */ ?>
            <?= Yii::t("filter", 'Сортування'); ?>:
            <select class="sort"
                   onchange='document.location.href = "<?= "{$uriPrefix}/"; ?>" + $(this).val() + "<?= "/{$period}/{$limit}"; ?>"'>
                <?php if ($category == 'own'): ?>
                    <option value="default" <?= ($sort == 'default') ? 'selected' : ''; ?>><?= Yii::t("filter", 'За замовчуванням'); ?></option>
                <?php endif; ?>
                <option value="desc" <?= ($sort == 'desc') ? 'selected' : ''; ?>><?= Yii::t("filter", 'Рейтинг по спаданню'); ?></option>
                <option value="asc" <?= ($sort == 'asc') ? 'selected' : ''; ?>><?= Yii::t("filter", 'Рейтинг по зростанню'); ?></option>
            </select>
        </span>
    </div>
<?php endif; ?>

<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV24-03 -->'; ?>

<?php if (YII_ENV == 'dev') : ?>
<h4>// END of FILE! </h4>
</div>
<?php endif; ?>

