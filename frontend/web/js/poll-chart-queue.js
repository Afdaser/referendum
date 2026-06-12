(function ($) {
    'use strict';

    // Централізований рендер графіків: читаємо всі конфіги та малюємо в одному циклі.
    $(function () {
        var chartQueue = [];

        $('.inner_container_graph[data-chart-config]').each(function () {
            var rawConfig = $(this).attr('data-chart-config');
            if (!rawConfig || $(this).data('chart-lazy')) {
                return;
            }

            try {
                chartQueue.push(JSON.parse(rawConfig));
            } catch (error) {
                // Не блокуємо решту графіків, якщо один конфіг пошкоджений.
                console.error('Некоректний JSON у data-chart-config:', error, rawConfig);
            }
        });

        for (var i = 0; i < chartQueue.length; i++) {
            var item = chartQueue[i] || {};
            if (!item.id || !item.category) {
                continue;
            }

            renderChart(item.category, item.id, item.title || '', item.series || [], item.pie || [], item.line || {});
            $('#' + item.id).data('chart-rendered', true);
        }
    });
})(jQuery);
