<?php

use yii\web\View;
use frontend\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
/* @var Poll $poll */
/* @var Poll[] $relatedPolls */
/* @var string|null $pageHeading */

if (!empty($poll->pollLanguage)) {
    Yii::$app->params['canonical'] = Yii::$app->urlManager->createPollLangUrl($poll->pollLanguage->name, '//poll/view', array('id' => $poll->id));
}

// Підтримуємо формат hash-посилання #reply-{id} як при першому відкритті,
// так і при кліку по «Відповісти» без перезавантаження сторінки.
$this->registerJs(<<<JS
(function () {
    function applyReplyHash() {
        var match = window.location.hash.match(/^#reply-(\d+)$/);
        if (!match) {
            return;
        }

        var replyId = match[1];
        var parentInput = document.querySelector('input[name="Profile[comment][parent_id]"]');
        if (parentInput) {
            parentInput.value = replyId;
        }

        var commentsTabTrigger = document.querySelector('a[href="#middle_text_input__comment_b"][data-toggle="tab"]');
        if (commentsTabTrigger && window.jQuery) {
            window.jQuery(commentsTabTrigger).tab('show');
        }

        var commentTextarea = document.querySelector('#middle_text_input__comment_b textarea[name="Profile[comment][content]"]');
        if (commentTextarea) {
            commentTextarea.focus();
        }
    }

    // Запускаємо логіку одразу при завантаженні сторінки.
    applyReplyHash();

    // Повторно запускаємо логіку, коли hash змінюється без reload.
    window.addEventListener('hashchange', applyReplyHash);
})();
JS
, View::POS_END);

?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <div class="chart_b" style="margin-bottom: 5px;">
            <div class="top_b_chart">
                <a class="btn_prev_var" href="<?= Yii::$app->request->referrer; ?>"><?= Yii::t('poll', 'Назад'); ?></a>
            </div>
            <div class="inner_b_chart">
                <!-- Додаємо мікророзмітку для DiscussionForumPosting -->
                <div class="poll_block poll_view_page" itemscope itemtype="https://schema.org/DiscussionForumPosting">
                    <meta itemprop="mainEntityOfPage" content="<?= Yii::$app->request->absoluteUrl;?>" />
                    <meta itemprop="url" content="<?= Yii::$app->request->absoluteUrl;?>" />

                    <div class="poll_heading_b clearfix">
                        <div class="left_rating_b" itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                            <?php // Кнопки рейтингу лишають класи та data-id для наявних jQuery-обробників. ?>
                            <button type="button" class="arrow_rating_top" data-id="<?= $poll->id; ?>" aria-label="<?= Yii::t('poll', 'Підняти рейтинг опитування'); ?>"></button><br>
                            <span class="poll_rating" itemprop="userInteractionCount" data-id="<?= $poll->id; ?>"><?= $poll->rating; ?></span><br>
                            <span itemprop="interactionType" content="https://schema.org/LikeAction"></span>
                            <button type="button" class="arrow_rating_down" data-id="<?= $poll->id; ?>" aria-label="<?= Yii::t('poll', 'Знизити рейтинг опитування'); ?>"></button>
                        </div>
                        <div class="middle_title_b">
                            <div class="title_poll poll_view_title_row">
                                <?php // Якщо налаштовано H1 у статичних мета-тегах — використовуємо його. ?>
                                <?php if (!empty($pageHeading)): ?>
                                    <?php // Переносимо itemprop="headline" безпосередньо на h1. ?>
                                    <h1 itemprop="headline"><?= $pageHeading; ?></h1>
                                    <?php if (!empty($poll->image_url)): ?>
                                        <?php // Alt береться з поля опитування, а за відсутності безпечно підставляємо назву. ?>
                                        <?= Html::img($poll->image_url, ['alt' => $poll->image_alt ?: $poll->title, 'class' => 'poll-view-image', 'itemprop' => 'image']); ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php // Додаємо префікс "Poll:" до заголовка опитування. ?>
                                    <?php // Переносимо itemprop="headline" безпосередньо на h1. ?>
                                    <h1 itemprop="headline"><?= Yii::t('poll', 'Опитування: {title}', ['title' => $poll->title]); ?></h1>
                                    <?php if (!empty($poll->image_url)): ?>
                                        <?php // Alt береться з поля опитування, а за відсутності безпечно підставляємо назву. ?>
                                        <?= Html::img($poll->image_url, ['alt' => $poll->image_alt ?: $poll->title, 'class' => 'poll-view-image', 'itemprop' => 'image']); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <span class="right_block_share_icon poll_view_share">
                                   <?php // Переміщаємо кнопку поширення ближче до заголовка для кращої читабельності. ?>
                                   <?= $this->render('//site/polls/_shareSocial', ['poll' => $poll]); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="middle_name_poll_b clearfix page">
                        <?php // Виносимо опис опитування в окремий блок для точнішого керування відступами. ?>
                        <div class="desc_my_chart" itemprop="text">
                            <?= $poll->getClearedDescribe() ?>
                        </div>
                        <?php $chartData = $poll->getChartData();?>
                        <?php $bar = StringHelper::formatForBar($chartData); ?>
                        <?php $pie = StringHelper::formatForPie($chartData); ?>
                        <?php if(!$poll->isShowResult()):?>
                            <div>
                                <?= $this->render('/poll/options', ['poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie]); ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="container_graph poll_chart_on_demand" style="display: none;">
                                <?php // Графік не показуємо одразу, але відкриваємо його після кліку на будь-який тип. ?>
                                <?= $this->render('/poll/options', ['poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie,'showChart'=>true,'lazyChart'=>true]); ?>
                            </div>
                        <?php endif;?>
                        <?php if($poll->isShowResult()):?>
                            <div class="clearfix"></div>
                            <div class="container_graph">
                                <?= $this->render('/poll/options', ['poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie]); ?>
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="top_poll_b clearfix bottom_space_for_chart poll_view_footer">
                        <div class="poll_view_tags">
                            <?php foreach ($poll->tags as $pollTag) : ?>
                                <a href="<?= $pollTag->url ?>" class="link_poll">#<?= $pollTag->name; ?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="poll_view_chart_toggle">
                            <span class="chosen_graph_b animated_b">
                                <span class="inner_chosen_graph">
                                    <?php // Активний перемикач синхронізуємо з result_type, зокрема для нового лінійного графіка. ?>
                                    <a href="javascript:void(0)" class="pie_chart <?= ($poll->result_type == 3) ? 'active' : ''; ?>" data-id="pie">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                    <a href="javascript:void(0)" class="horizontal_b_chart <?= (!$poll->result_type || $poll->result_type == 1) ? 'active' : ''; ?>" data-id="bar">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                    <a href="javascript:void(0)" class="vertical_b_chart <?= ($poll->result_type == 2) ? 'active' : ''; ?>" data-id="column">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                    <a href="javascript:void(0)" class="line_chart <?= ($poll->result_type == 4) ? 'active' : ''; ?>" data-id="line">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="show_voices_b">
                        <div class="title_show_voice clearfix">
                            <span class="toggle_b collapsed" data-toggle="collapse" data-target=".for_collapsing">
                                <?= Yii::t('poll', 'Показати голоси'); ?>
                                <i class="fa fa-angle-up"></i>
                            </span>
                            <span class="right_clear_btn">
                                <a href="javascript:void(0)" class="clear_btn">
                                    <i class="clear_btn_ico"></i>
                                    <span><?= Yii::t('poll', 'Очистити'); ?></span>
                                </a>
                            </span>
                        </div>
                        <div class="for_collapsing collapse">
                            <div class="select_blocks">
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Стать'); ?><br>
                                    <select class="gender">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <?php $sexes = User::getUserSexList();?>
                                        <?php foreach($sexes as $index=>$sex): ?>
                                            <option value="<?= $index; ?>"><?= $sex; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Вік'); ?><br>
                                    <select class="age">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <?php $ages = User::getUserAgeIntervalList();?>
                                        <?php foreach($ages as $index=>$age): ?>
                                            <option value="<?= $index; ?>"><?= $age; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Країна'); ?><br>
                                    <select class="country">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <?php foreach($poll->getVoteCountries() as $key => $country): ?>
                                            <option value="<?= $key; ?>"><?= $country; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                                <!--
                                <span class="item_show region">
                                    <?= Yii::t('poll', 'Регіон'); ?><br>
                                    <input type="text" id="region" style="display: none">
                                    <input type="text" class="autocomplete region" id="regionAC">
                                    <a href="#" class="del_btn"></a>
                                    <div class="autocomplete-suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div>
                                </span>
                                -->
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Реєстрація'); ?><br>
                                    <select class="registration">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <option value="1"><?= Yii::t('poll', 'Зареєстровані'); ?></option>
                                        <option value="2"><?= Yii::t('poll', 'Незареєстровані'); ?></option>
                                    </select>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bottom_poll_b clearfix">
                        <span class="item_bottom_poll author-block" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <?= Yii::t('poll', 'Користувач'); ?>:
                            <a href="<?= Url::toRoute(['/poll/site/user-profile','id'=>$poll->user_id ]);?>" itemprop="url">
                                <span itemprop="name"><?= $poll->author->getFullUserName();?></span>
                            </a>
                        </span>
                        <span class="item_bottom_poll"><?= Yii::t('poll', 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?></span>
                        <?php
$date = new DateTime($poll->date_add, new DateTimeZone('UTC')); // якщо дата в БД у UTC
$date->setTimezone(new DateTimeZone('America/New_York'));
?>
<span class="item_bottom_poll" itemprop="datePublished" content="<?= $date->format('c'); ?>">
    <?php // Виводимо лише дату без часу для сторінки тегів. ?>
    <?= $date->format('Y-m-d'); ?>
</span>

                        <?php if ($poll->isOpen()): ?>
                            <?php /* Коментар: приховано статус відкритого опитування на сторінці перегляду. */ ?>
                            <?php /*
                            <div class="right_poll_status open">
                                <?= Yii::t('poll', 'Відкрите голосування'); ?>
                                <span><i class="fa fa-unlock"></i></span>
                            </div>
                            */ ?>
                        <?php else: ?>
                            <div class="right_poll_status closed" title="<?= $poll->countPollOptionsVoters; ?>/<?= $poll->votes_count_close;?>">
                                <?= Yii::t('poll', 'Закрите голосування'); ?>
                                <span><i class="fa fa-lock"></i></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php // Блок показуємо тільки якщо знайшли щонайменше одне схоже опитування. ?>
                    <?php if (!empty($relatedPolls)): ?>
                        <div class="related-polls-block">
                            <h3 class="related-polls-title"><?= Yii::t('poll', 'Схожі опитування'); ?></h3>
                            <div class="related-polls-list">
                                <?php foreach ($relatedPolls as $relatedPoll): ?>
                                    <div class="related-polls-item">
                                        <?php // Лічильник виносимо за межі посилання, але зберігаємо попередній візуальний вигляд. ?>
                                        <span class="related-polls-score"><?= (int) $relatedPoll->countPollOptionsVoters; ?></span>
                                        <a class="related-polls-link" href="<?= Url::toRoute(['/poll/poll/view', 'id' => $relatedPoll->id]); ?>">
                                            <span class="related-polls-link-text"><?= Html::encode($relatedPoll->title); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="comments_add-answer_b">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"> <!-- ТЕПЕР активна вкладка "Коментарі" -->
            <a href="#middle_comments_list_b" role="tab" data-toggle="tab">
                <?= Yii::t('poll', 'Коментарі'); ?>
                <span class="count_poll" itemprop="commentCount"><?= count($poll->pollComments); ?></span>
            </a>
        </li>
        <li>
            <a href="#inner_add-answer_b" role="tab" data-toggle="tab">
                <?= Yii::t('poll', 'Запропоновані відповіді'); ?>
                <span class="count_poll"><?= count($poll->pollAnswers); ?></span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="inner_add-answer_b" class="inner_add-answer_b tab-pane">
            <?= $this->render('/poll/answersBlock', ['answers' => $poll->pollAnswers]); ?>
        </div>
        <div id="middle_comments_list_b" class="middle_comments_list_b tab-pane active"> <!-- ТЕПЕР активний блок коментарів -->
            <?= $this->render('/poll/commentsBlock', ['comments' => $poll->pollCommentsRoot]); ?>
        </div>
    </div>
    <div class="comments_block_bottom">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#middle_text_input__comment_b" role="tab" data-toggle="tab"><?= Yii::t('poll', 'Додати коментар'); ?></a></li>
            <li><a href="#middle_text_input_b" role="tab" data-toggle="tab"><?= Yii::t('poll', 'Додати варіант відповіді'); ?></a></li>
        </ul>
        <div class="tab-content">
            <div id="middle_text_input_b" class="middle_text_input_b tab-pane">
                <form method="post" action="<?= Url::toRoute(['/poll/poll/add-answer']); ?>">
                    <?= yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken); ?>
                    <input name="Profile[answer][poll_id]" type="text" class="autocomplete" value="<?= $poll->id;?>" hidden>
                    <textarea id="answer_text" maxlength="60" name="Profile[answer][title]"><?= $answerModel->title;?></textarea>
                    <div class="count_symbols">
                        <?= Yii::t('poll', 'Залишилось'); ?>: <span id="textareaFeedback">60</span> <?= Yii::t('poll', 'символів'); ?>
                    </div>
                    <div class="bottom_btn_b">
                        <button type="submit" class="send_btn"><?= Yii::t('poll', 'Надіслати'); ?><i class="send_icon"></i></button>
                    </div>
                </form>
            </div>
            <div id="middle_text_input__comment_b" class="middle_text_input_b comments tab-pane active">
                <form method="post" action="<?= Url::toRoute(['/poll/poll/add-comment']); ?>">
                    <?= yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken); ?>
                    <input name="Profile[comment][poll_id]" type="text" class="autocomplete" value="<?= $poll->id;?>" hidden>
                    <input name="Profile[comment][parent_id]" type="text" class="autocomplete" value="<?= isset($commentModel->parent_id)?$commentModel->parent_id:'';?>" hidden>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?php // Для гостьового коментаря робимо окремий дружній блок з ніком. ?>
                        <div class="guest-comment-author-field">
                            <label for="guest-comment-nickname"><?= Yii::t('poll', 'Нікнейм'); ?></label>
                            <input
                                id="guest-comment-nickname"
                                name="Profile[comment][guest_nickname]"
                                class="guest-comment-author-input"
                                type="text"
                                maxlength="60"
                                placeholder="<?= Yii::t('poll', 'Ваш нік'); ?>"
                                value="<?= $commentModel->guest_nickname ?? ''; ?>"
                            >
                            <p class="guest-comment-author-hint"><?= Yii::t('poll', 'Вкажіть нікнейм, під яким буде опубліковано коментар.'); ?></p>
                        </div>
                    <?php endif; ?>
                    <textarea name="Profile[comment][content]"><?= $commentModel->content;?></textarea>
                    <div class="bottom_btn_b">
                        <button type="submit" class="send_btn"><?= Yii::t('poll', 'Надіслати'); ?><i class="send_icon"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<div class="info_block">
    <?php
    // Дозволяємо перевизначити стандартний блок статистики через адмінку.
    $infoBlockData = $poll->getStaticInfoBlockData();
    $customInfoBlock = $poll->getStaticInfoBlock($infoBlockData);
    $faqList = $poll->getFaqList();
    ?>
    <?php if ($customInfoBlock !== null): ?>
        <?= $customInfoBlock; ?>
    <?php else: ?>
        <h2 itemprop="alternativeHeadline">
            <?= Yii::t('poll', 'Коротка статистика та результати опитування "{title}"', ['title' => $poll->title]); ?>
        </h2>

        <?php
        $stats = $infoBlockData['stats'];
        $registeredVotes = $infoBlockData['registeredVotes'];
        $guestVotes = $infoBlockData['guestVotes'];
        $mostPopular = $infoBlockData['mostPopular'];
        $commentsCount = $infoBlockData['commentsCount'];
        ?>

        <p itemprop="text">
            <?= Yii::t('poll',
                'Це онлайн-опитування було створено {date}. Наразі воно зібрало {votes} голосів, серед яких {reg} зареєстрованих користувачів і {guest} незареєстрованих, та {comments} коментарів, відображаючи поточну громадську думку та результати голосування.',
                [
                    'title' => $poll->title,
                    'date' => $infoBlockData['date'],
                    'votes' => $infoBlockData['votesTotal'],
                    'reg' => $registeredVotes,
                    'guest' => $guestVotes,
                    'comments' => $commentsCount,
                ]
            ); ?>

            <?php if (!empty($poll->tags)): ?>
                <?= Yii::t('poll', 'Це опитування стосується таких тем: {tags}', [
                    'tags' => $infoBlockData['tagsHtml'],
                ]); ?>.
            <?php endif; ?>
        </p>

        <?php if (!empty($stats)): ?>
            <p><?= Yii::t('poll', 'В цьому публічному опитуванні та опитуванні громадської думки представлені такі варіанти відповідей:') ?></p>
            <?= $infoBlockData['optionsListHtml']; ?>

            <?php if ($mostPopular): ?>
                <p>
                    <?= Yii::t('poll',
                        'Як видно з опитування «{title}» найбільше вибрали варіант «{answer}». За нього проголосували {votes} голосів і це {percent}% від всього голосування.',
                        [
                            'title' => $poll->title,
                            'answer' => Html::encode($mostPopular['title']),
                            'votes' => $mostPopular['votes'],
                            'percent' => number_format($mostPopular['percent'], 1),
                        ]
                    ); ?>
                </p>
            <?php endif; ?>

        <?php else: ?>
            <p class="muted"><?= Yii::t('poll', 'У цьому опитуванні ще немає варіантів відповіді.'); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($faqList)): ?>
        <?php // Рендеримо один спільний FAQ-блок: статичний + індивідуальний для цього опитування. ?>
        <div class="tag-faq" itemscope itemtype="https://schema.org/FAQPage">
            <h2><?= Yii::t('poll', 'FAQ'); ?></h2>
            <?php foreach ($faqList as $faqIndex => $faqItem): ?>
                <div class="faq_entry" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                    <button class="faq_question" type="button" aria-expanded="false" aria-controls="poll-faq-answer-<?= $faqIndex; ?>">
                        <span class="faq_question-text" itemprop="name"><?= Html::encode($faqItem['question']); ?></span>
                    </button>
                    <div class="faq_answer" id="poll-faq-answer-<?= $faqIndex; ?>" hidden itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                        <div itemprop="text"><?= nl2br(Html::encode($faqItem['answer'])); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>


</div><!-- end .comments_add-answer_b -->
                </div><!-- end .poll_block -->
            </div>
        </div>
        <?php if(0 && Yii::$app->user->isGuest){ ?>
            <a data-target="#registrtion_step_1" data-toggle="modal" href="#" class="item_list_poll" style="text-align:center;padding-left:70px;padding-right:70px;">
                <?= Yii::t('poll', 'Для того щоб прийняти участь в опитуванні, або побачити результати необхідно зареєструватись.'); ?>
            </a>
        <?php } ?>
        <?php if($poll->isShowResult() && !$poll->isVoted()){?>
            <?php /* Можна додати повідомлення для користувача, якщо треба */ ?>
        <?php } ?>
    </div>
</div>

<?php
$pollId = $poll->id;
$pollTitle = Json::encode($poll->title);

$js = <<<JS
(function(){var faqButtons=document.querySelectorAll('.tag-faq .faq_question');faqButtons.forEach(function(button){button.addEventListener('click',function(){var entry=button.closest('.faq_entry');if(!entry){return;}var isOpen=entry.classList.toggle('is-open');button.setAttribute('aria-expanded',isOpen?'true':'false');var answerId=button.getAttribute('aria-controls');var answer=answerId?document.getElementById(answerId):null;if(answer){answer.hidden=!isOpen;}});});})();
var maxLength = jQuery('#answer_text').attr('maxlength');
jQuery('#answer_text').on('keyup', function () {
    var curLength = jQuery('#answer_text').val().length;
    jQuery(this).val(jQuery(this).val().substr(0, maxLength));
    var remaning = maxLength - curLength;
    if (remaning < 0) remaning = 0;
    jQuery('#textareaFeedback').html(remaning);
    if (remaning < 10) {
        jQuery('#textareaFeedback').addClass('warning');
    } else {
        jQuery('#textareaFeedback').removeClass('warning');
    }
});

 jQuery(document).on('change', '.gender, .age, .country, .registration', function () {
     filterDataChart($pollId, $pollTitle);
 });

jQuery(document).on('click', '.clear_btn', function () {
    clearChartFilters($pollId, $pollTitle);
});

/*
jQuery(document).on('change', '.country', function () {
    refreshRegions(jQuery('.country').val(), jQuery('span.region'), 'regionAC', 'region', 'cityAC', jQuery('.city'), 'city');
    jQuery('#regionAC').val('');
    jQuery('#region').val(0);
});

jQuery(document).on('click', '.del_btn', function () {
    jQuery('#regionAC').val('');
    jQuery('#region').val(0);
});

jQuery(document).on('click', '.autocomplete-suggestion', function () {
    filterDataChart($pollId, $pollTitle);
});
*/

jQuery(document).on('click', '.pie_chart,.horizontal_b_chart,.vertical_b_chart,.line_chart', function () {
    jQuery('#container$pollId').removeClass('pie').removeClass('bar').removeClass('column').removeClass('line');
    jQuery('#container$pollId').addClass(jQuery(this).attr('data-id'));
    filterDataChart($pollId, $pollTitle);
});
JS;

$this->registerJs($js, View::POS_READY);

if (isset($error) && $error) {
    $this->registerJs('alert(' . Json::encode(strip_tags($error)) . ');', View::POS_READY);
}
?>
