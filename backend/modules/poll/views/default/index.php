<?php

use yii\web\View;
use dosamigos\chartjs\ChartJs;

$this->title = Yii::t('app', 'Dashboard');

/* @var $this yii\web\View */
?>
<section class="content">
    <div class="row">
        <?php if (!empty($data['monthly_diagram']['labels'])): ?>
            <div class="col-md-12">
                <!-- AREA CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'last votes'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <?=
                            ChartJs::widget([
                                'type' => 'bar',
                                'options' => [
                                    'height' => 80,
                                    'width' => 400
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

