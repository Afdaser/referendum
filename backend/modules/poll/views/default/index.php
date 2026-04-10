<?php

use yii\web\View;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Json;
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
                        <?php
                        // Початкове значення: загальна кількість голосів за всі 12 місяців.
                        $initialVisibleTotal = array_sum($data['monthly_diagram']['active']) + array_sum($data['monthly_diagram']['inactive']);
                        ?>
                        <div class="row" style="margin-bottom: 12px;">
                            <div class="col-sm-8">
                                <strong><?= Yii::t('app', 'Показано голосів:') ?></strong>
                                <span id="visible-votes-total"><?= number_format((int)$initialVisibleTotal, 0, '.', ' ') ?></span>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button id="restore-all-months" type="button" class="btn btn-default btn-sm">
                                    <?= Yii::t('app', 'Повернути всі місяці') ?>
                                </button>
                            </div>
                        </div>
                        <div id="hidden-months-list" style="margin-bottom: 10px;"></div>
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
                                    // Клік по колонці приховує/повертає місяць у графіку.
                                    'onClick' => new JsExpression("function(evt, activeEls) {
                                        if (!activeEls || !activeEls.length) {
                                            return;
                                        }
                                        var index = activeEls[0]._index;
                                        if (window.toggleMonthlyChartIndex) {
                                            window.toggleMonthlyChartIndex(index);
                                        }
                                    }"),
                                    'responsive' => true,
                                    'maintainAspectRatio' => false,
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

<?php
if (!empty($data['monthly_diagram']['labels'])) {
    $labelsJson = Json::htmlEncode($data['monthly_diagram']['labels']);
    $this->registerJs(<<<JS
        (function () {
            var chart = window.chartJS_monthly_votes_chart;
            if (!chart) {
                return;
            }

            // Зберігаємо оригінальні дані, щоб коректно повертати вимкнені місяці.
            var originalData = chart.data.datasets.map(function (dataset) {
                return dataset.data.slice();
            });
            var monthLabels = {$labelsJson};
            var hiddenIndices = {};
            var totalNode = document.getElementById('visible-votes-total');
            var hiddenListNode = document.getElementById('hidden-months-list');
            var restoreButton = document.getElementById('restore-all-months');

            function formatNumber(value) {
                return String(value).replace(/\\B(?=(\\d{3})+(?!\\d))/g, ' ');
            }

            function updateVisibleTotal() {
                var sum = 0;
                chart.data.datasets.forEach(function (dataset) {
                    dataset.data.forEach(function (value) {
                        if (typeof value === 'number') {
                            sum += value;
                        }
                    });
                });
                totalNode.textContent = formatNumber(sum);
            }

            function renderHiddenMonths() {
                var hiddenLabels = Object.keys(hiddenIndices).map(function (idx) {
                    return monthLabels[parseInt(idx, 10)];
                });
                if (!hiddenLabels.length) {
                    hiddenListNode.innerHTML = '';
                    return;
                }

                // Показуємо адміністратору, які місяці приховано.
                hiddenListNode.innerHTML = '<small><strong>Приховано:</strong> ' + hiddenLabels.join(', ') + '</small>';
            }

            function setMonthVisibility(index, visible) {
                chart.data.datasets.forEach(function (dataset, datasetIndex) {
                    dataset.data[index] = visible ? originalData[datasetIndex][index] : null;
                });
                if (visible) {
                    delete hiddenIndices[index];
                } else {
                    hiddenIndices[index] = true;
                }
                chart.update();
                updateVisibleTotal();
                renderHiddenMonths();
            }

            window.toggleMonthlyChartIndex = function (index) {
                var currentlyHidden = !!hiddenIndices[index];
                setMonthVisibility(index, currentlyHidden);
            };

            restoreButton.addEventListener('click', function () {
                Object.keys(hiddenIndices).forEach(function (idx) {
                    setMonthVisibility(parseInt(idx, 10), true);
                });
            });

            updateVisibleTotal();
            renderHiddenMonths();
        })();
JS
    , View::POS_READY);
}
?>
