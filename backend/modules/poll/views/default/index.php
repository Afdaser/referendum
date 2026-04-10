<?php

use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Dashboard');

/* @var $this yii\web\View */
?>
<section class="content">
    <?php if (!empty($data['monthly_diagram']['labels'])): ?>
        <!-- Окремий рядок з великими числами, щоб адміністратор одразу бачив динаміку місяця. -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body text-center" style="padding: 28px 16px;">
                        <div style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3c8dbc;">
                            <?= Yii::t('app', 'ЦЬОГО МІСЯЦЯ') ?>
                        </div>
                        <div style="font-size: 56px; line-height: 1.1; font-weight: 800; color: #1f2d3d; margin-top: 8px;">
                            <?= number_format((int)($data['monthly_totals']['current'] ?? 0), 0, '.', ' ') ?>
                        </div>
                        <div style="font-size: 14px; color: #6c757d; margin-top: 6px;">
                            <?= Yii::t('app', 'голосів в опитуваннях') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-body text-center" style="padding: 28px 16px;">
                        <div style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #00acd6;">
                            <?= Yii::t('app', 'МИНУЛОГО МІСЯЦЯ') ?>
                        </div>
                        <div style="font-size: 56px; line-height: 1.1; font-weight: 800; color: #1f2d3d; margin-top: 8px;">
                            <?= number_format((int)($data['monthly_totals']['previous'] ?? 0), 0, '.', ' ') ?>
                        </div>
                        <div style="font-size: 14px; color: #6c757d; margin-top: 6px;">
                            <?= Yii::t('app', 'голосів в опитуваннях') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($data['monthly_diagram']['labels'])): ?>
            <div class="col-md-12">
                <!-- AREA CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'last votes'); ?></h3>
                    </div>
                    <div class="box-body">
                        <!-- Повертаємо розмір графіка до історичного вигляду, як до PR #294. -->
                        <div class="chart">
                            <?=
                            ChartJs::widget([
                                'type' => 'bar',
                                'options' => [
                                    'id' => 'monthly_votes_chart',
                                    'height' => 80,
                                    'width' => 400
                                ],
                                'clientOptions' => [
                                    // Клік по стовпчику прибирає відповідний місяць з усіх серій.
                                    'onClick' => new JsExpression("function(e, a) {
                                        if (!a || !a.length) return;
                                        var i = (a[0].index !== undefined) ? a[0].index : a[0]._index, d = this.data;
                                        if (i === undefined || i < 0 || i >= d.labels.length) return;
                                        d.labels.splice(i, 1); d.datasets.forEach(function(s) { s.data.splice(i, 1); });
                                        this.update();
                                    }"),
                                    // Не змінюємо поведінку ресайзу, щоб зберегти історичну висоту графіка.
                                ],
                                'data' => [
                                    'labels' => $data['monthly_diagram']['labels'],
                                    'datasets' => [
                                        [
                                            'label' => Yii::t('app', 'Total of votes `Registred`'),
                                            'backgroundColor' => "rgba(78,115,223,0.8)",
                                            'borderColor' => "rgba(68,114,196,1)",
                                            'pointBackgroundColor' => "rgba(179,181,198,1)",
                                            'pointBorderColor' => "#fff",
                                            'pointHoverBackgroundColor' => "#fff",
                                            'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                            'data' => $data['monthly_diagram']['active'],
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Total of votes `Guest`'),
                                            'backgroundColor' => "rgba(28, 200, 138, 0.9)",
                                            'borderColor' => "rgba(255,99,132,1)",
                                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                                            'pointBorderColor' => "#fff",
                                            'pointHoverBackgroundColor' => "#fff",
                                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                            'data' => $data['monthly_diagram']['inactive'],
                                        ]
                                    ]
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
