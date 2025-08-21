<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */

$qtyPolls = count($user->polls);
$qtyPollComments = count($user->pollComments);
?>

<?php $this->beginContent('@app/modules/user/views/admin/update.php', ['user' => $user]) ?>

<table class="table">
    <tr>
        <td><strong><?= Yii::t('user', 'Registration time') ?>:</strong></td>
        <td><?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$user->created_at]) ?></td>
    </tr>
    <?php if ($user->registration_ip !== null): ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Registration IP') ?>:</strong></td>
            <td><?= $user->registration_ip ?></td>
        </tr>
    <?php endif ?>
    <tr>
        <td><strong><?= Yii::t('user', 'Confirmation status') ?>:</strong></td>
        <?php if ($user->isConfirmed): ?>
            <td class="text-success">
                <?= Yii::t('user', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$user->confirmed_at]) ?>
            </td>
        <?php else: ?>
            <td class="text-danger"><?= Yii::t('user', 'Unconfirmed') ?></td>
        <?php endif ?>
    </tr>
    <tr>
        <td><strong><?= Yii::t('user', 'Block status') ?>:</strong></td>
        <?php if ($user->isBlocked): ?>
            <td class="text-danger">
                <?= Yii::t('user', 'Blocked at {0, date, MMMM dd, YYYY HH:mm}', [$user->blocked_at]) ?>
            </td>
        <?php else: ?>
            <td class="text-success"><?= Yii::t('user', 'Not blocked') ?></td>
        <?php endif ?>
    </tr>
</table>

<div class="poll-view">
    <p>
        <?= Html::a(Yii::t('app', 'Polls'). ($qtyPolls ? ' ['.$qtyPolls.']' : ''),
                ['/poll/poll/index', 'PollSearch' => ['user_id' =>  $user->id]], ['class' => 'btn btn-primary']
                ) ?>
        <?php if(!empty($qtyPollComments)) : ?>
        <?= Html::a(Yii::t('app', 'Comments'). ($qtyPollComments ? ' ['.$qtyPollComments.']' : ''),
                ['/poll/poll-comment/index', 'PollCommentSearch' => ['user_id' =>  $user->id]], ['class' => 'btn btn-primary']
                ) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $user->profile,
        'attributes' => [
            'name',
            'lastname',
            'country.name',
            'region.name',
            'city.name',
            'date_birthday:date',
            'phone',
            'public_email',
//            'gender',
//            'author.name',
//            'rating',
//            'statusName',
//            'show_on_slider:boolean',
//            'votes_count_close',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
<?php $this->endContent() ?>
