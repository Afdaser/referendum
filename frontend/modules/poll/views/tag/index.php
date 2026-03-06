<?php

use frontend\widgets\WPollList;
use Yii;
use yii\helpers\Html;

/** @var yii\web\View $this */

$qty = $dataProvider->count;
$defaultTitle = Yii::t('tag', 'Останні опитування {tag} та думка громадськості | Referendum', [
    'tag' => $tagModel->name,
]);
$defaultDescription = Yii::t('tag', 'Опитування та думки щодо {tag}: беріть участь у {count} захопливих опитуваннях, висловлюйте свої думки, коментуйте актуальні питання, спілкуйтеся з іншими та досліджуйте результати.', [
    'count' => $tagModel->polls_count,
    'tag' => $tagModel->name,
]);
$meta = $tagModel->getStaticMeta();

$pageTitle = $meta['title'] ?? $defaultTitle;
Yii::$app->page->title = $pageTitle;
$this->title = $pageTitle;
Yii::$app->page->description = $meta['description'] ?? $defaultDescription;
$pageHeading = $meta['heading'] ?? null;
$faqList = $tagModel->getFaqList();
$tagDescription = trim((string) $tagModel->description);
$latestActivityBlock = $tagModel->getLatestActivityBlockHtml();
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <?php // Виділяємо головний заголовок, щоб він одразу відрізнявся від рекламних блоків. ?>
        <?php if ($pageHeading !== null): ?>
            <h1 class="tag-page__title"><?= $pageHeading; ?></h1>
        <?php else: ?>
            <h1 class="tag-page__title">Latest <?= Html::encode($tag); ?> public opinion polls</h1>
        <?php endif; ?>
<?php if ($qty) : ?>
    <?= WPollList::widget([
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'searchForm' => $searchForm,
    ]) ?>
<?php else: ?>
        <div class="chart_b" style="margin-bottom: 5px;">
            <h1>tag/index</h1>
            <h4><?= Yii::t('app', 'No matching results'); ?></h4>
            <p>
                You may change the content of this page by modifying
                the file <code><?= __FILE__; ?></code>.
            </p>
        </div>
<?php endif; ?>

<?php if ($page <= 1) : ?>
        <?php if ($tagDescription !== '') : ?>
            <?php // Власноручний опис тега показуємо першим із додаткових блоків. ?>
            <div class="info_block tag-description">
                <?= $tagDescription; ?>
            </div>
        <?php endif; ?>
        <div class="info_block">
            <h2><?= Yii::t('tag', 'Найцікавіші опитування на тему "{tag}"', ['tag' => $tagModel->name]); ?></h2>
            <p><?= $tagModel->getInfoText(); ?></p>
        </div>
        <?php if ($latestActivityBlock !== '') : ?>
            <div class="info_block tag-latest-activity">
                <?php // Блок активності показуємо лише коли адміністратор явно задав шаблон. ?>
                <?= $latestActivityBlock; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($faqList)) : ?>
            <?php // Додатковий блок Schema.org FAQPage для тегу. ?>
            <div class="info_block tag-faq" itemscope itemtype="https://schema.org/FAQPage">
                <?php // Заголовок FAQ блоку для тегу показує ключову фразу з урахуванням підстановок змінних. ?>
                <h2><?= Yii::t('tag', 'Часті запитання про опитування на тему {tag}', ['tag' => $tagModel->name]); ?></h2>
                <?php foreach ($faqList as $index => $faqItem): ?>
                    <div class="faq_entry" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                        <button
                            class="faq_question"
                            type="button"
                            aria-expanded="false"
                            aria-controls="faq-answer-<?= $index; ?>"
                        >
                            <span class="faq_question-text" itemprop="name">
                                <?= $faqItem['question']; ?>
                            </span>
                        </button>
                        <div
                            class="faq_answer"
                            id="faq-answer-<?= $index; ?>"
                            itemprop="acceptedAnswer"
                            itemscope
                            itemtype="https://schema.org/Answer"
                            hidden
                        >
                            <div itemprop="text">
                                <?= $faqItem['answer']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            // Лаконічний скрипт для перемикання FAQ: не потребує додаткових залежностей.
            $faqToggleJs = <<<JS
(function () {
    var faqButtons = document.querySelectorAll('.tag-faq .faq_question');
    if (!faqButtons.length) {
        return;
    }
    faqButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var entry = button.closest('.faq_entry');
            var answerId = button.getAttribute('aria-controls');
            var answer = answerId ? document.getElementById(answerId) : null;
            if (!entry || !answer) {
                return;
            }
            var isOpen = entry.classList.contains('is-open');
            entry.classList.toggle('is-open', !isOpen);
            button.setAttribute('aria-expanded', String(!isOpen));
            if (isOpen) {
                answer.setAttribute('hidden', 'hidden');
            } else {
                answer.removeAttribute('hidden');
            }
        });
    });
})();
JS;
            $this->registerJs($faqToggleJs, \yii\web\View::POS_READY);
            ?>
        <?php endif; ?>
<?php endif; ?>

    </div>
</div>
