<?php

use yii\web\View;
use dosamigos\chartjs\ChartJs;

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
                        <!-- Розтягуємо графік по вертикалі майже на весь екран, щоб дрібні значення було краще видно. -->
                        <div class="chart" style="height: calc(100vh - 260px); min-height: 620px;">
                            <?=
                            ChartJs::widget([
                                'type' => 'bar',
                                'options' => [
                                    'style' => 'height: 100%; width: 100%;'
                                ],
                                'clientOptions' => [
                                    'maintainAspectRatio' => false,
                                    'scales' => [
                                        'yAxes' => [[
                                            'ticks' => [
                                                'beginAtZero' => true,
                                            ],
                                        ]],
                                    ],
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
