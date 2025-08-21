<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <div class="chart_b" style="margin-bottom: 5px;">
            <div class="site-error">

                <h1><?= Html::encode($this->title) ?></h1>

                <div class="alert alert-danger">
                    <?= nl2br(Html::encode($message)) ?>
                </div>

                <p>
                    The above error occurred while the Web server was processing your request.
                </p>
                <p>
                    Please contact us if you think this is a server error. Thank you.
                </p>

            </div>
        </div>
    </div>
</div>
