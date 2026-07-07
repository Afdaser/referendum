<?php
// Yii 2
// /var/www/vhosts_yii/referendum.social/referendum.social.local/frontend/widgets/views/filters/_sort.php

use yii\helpers\Url;
use yii\helpers\Html;

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

<?php
    // Мікророзмітка профілю потребує підготовлених значень, тому збираємо їх один раз.
    $profile = $user->profile;
    $profileUrl = Url::to(['/poll/site/user-profile', 'id' => $user->id], true);
    $dateCreated = $user->created_at ? Yii::$app->formatter->asDatetime($user->created_at, 'php:c') : null;
    $dateModified = $user->updated_at ? Yii::$app->formatter->asDatetime($user->updated_at, 'php:c') : null;
    $profileDescription = $profile && $profile->bio ? $profile->bio : ($profile->preferences ?? '');
?>

<?php if (YII_DEBUG || !empty($debug)) :?>
<div style="border:2px dashed red;"><?= '#DEV03:block01 [if ($user)]'; ?></div>
<?php endif; ?>

    <div class="chart_b user_page_b" itemscope itemtype="https://schema.org/ProfilePage">
        <?php if ($dateCreated): ?>
            <meta itemprop="dateCreated" content="<?= Html::encode($dateCreated); ?>">
        <?php endif; ?>
        <?php if ($dateModified): ?>
            <meta itemprop="dateModified" content="<?= Html::encode($dateModified); ?>">
        <?php endif; ?>
        <meta itemprop="url" content="<?= Html::encode($profileUrl); ?>">
        <div class="top_b_chart">
            <a class="btn_prev_var"
               href="<?=  Yii::$app->request->referrer; ?>"><?= Yii::t("poll", 'Назад'); ?><?= (YII_ENV == 'dev') ? '#DEV24-03': ''; ?></a>
        </div>
        <div class="my_profile_b" itemprop="mainEntity" itemscope itemtype="https://schema.org/Person">
            <meta itemprop="identifier" content="<?= Html::encode((string) $user->id); ?>">
            <?php if ($user->username): ?>
                <meta itemprop="alternateName" content="<?= Html::encode($user->username); ?>">
            <?php endif; ?>
            <?php if ($profile && $profile->public_email): ?>
                <meta itemprop="email" content="<?= Html::encode($profile->public_email); ?>">
            <?php endif; ?>
            <?php if ($profileDescription): ?>
                <meta itemprop="description" content="<?= Html::encode($profileDescription); ?>">
            <?php endif; ?>
            <?php if ($profile && $profile->website): ?>
                <link itemprop="sameAs" href="<?= Html::encode($profile->website); ?>">
            <?php endif; ?>
            <div class="profile_name" itemprop="name">
                <?= Html::encode($user->getFullUserName()); ?>
            </div>
            <div class="text_my_profile_data">
                <?php
                // Текстові лічильники нижче локалізуються через категорію "poll",
                // щоб переклади профілю були доступні на всіх піддоменах.
                // Позначаємо рейтинг як LikeAction, щоб пошуковики розуміли «карму» профілю.
                ?>
                <span class="profile_rating"
                      itemprop="interactionStatistic"
                      itemscope
                      itemtype="https://schema.org/InteractionCounter">
                    <meta itemprop="interactionType" content="https://schema.org/LikeAction">
                    <?= Yii::t("poll", 'Рейтинг користувача'); ?>:
                    <span itemprop="userInteractionCount"><?= Html::encode(User::getUserRating($user->id)); ?></span>
                </span><br>
                <span itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                    <meta itemprop="interactionType" content="https://schema.org/WriteAction">
                    <?= Yii::t("poll", 'Всього опитувань'); ?>:
                    <span itemprop="userInteractionCount"><?= User::getPollsCount($user->id); ?></span>
                </span><br>
                <span itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                    <meta itemprop="interactionType" content="https://schema.org/CommentAction">
                    <?= Yii::t("poll", 'Всього коментарів'); ?>:
                    <span itemprop="userInteractionCount"><?= $user->commentsCount; ?></span>
                </span>
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
    <?php
    $isTagPage = Yii::$app->controller->id === 'tag';
    // На сторінках тегів не дублюємо пошукову форму, адже поруч уже є статистика та FAQ.
    if (!$isTagPage):
    ?>
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
    <?php endif; ?>
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
    <?php // Додаємо окремий клас для сучасного вигляду фільтрів на головній стрічці без зміни логіки сортування. ?>
    <div class="sort_b main-polls-sort">
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
                <?php /* Ущільнили HTML-розмітку select/options: менше переносів рядків без змін DOM/CSS-класів. */ ?>
                <select onchange='document.location.href = "<?= "{$uriPrefix}/{$sort}/"; ?>" + $(this).val() + "<?= "/{$limit}?click=true"; ?>"'>
                    <?php /* Опція "за весь час" потрібна для показу всіх опитувань за замовчуванням. */ ?>
                    <option value="all" <?php if ($period == 'all'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за весь час'); ?></option>
                    <option value="day" <?php if ($period == 'day'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за день'); ?></option>
                    <option value="week" <?php if ($period == 'week'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за неділю'); ?></option>
                    <option value="month" <?php if ($period == 'month'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за місяць'); ?></option>
                    <option value="halfyear" <?php if ($period == 'halfyear'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за півроку'); ?></option>
                    <option value="year" <?php if ($period == 'year'): ?>selected<?php endif; ?>><?= Yii::t("filter", 'за рік'); ?></option>
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
