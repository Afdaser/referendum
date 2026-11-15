var slider;
var sliderResizeTimer;
var sliderLayout;
$(document).ready(function(){
    // Банер застарілого браузера вимкнено, тож жодних перевірок outdatedBrowser не запускаємо.
    initSliderHomepage();
    hoverGraphBtn();
    $('.slider_next').click(function(e){
        if (slider) {
            slider.goToNextSlide();
        }
        e.preventDefault();
    });
    $('.slider_prev').click(function(e){
        if (slider) {
            slider.goToPrevSlide();
        }
        e.preventDefault();
    });
    $('.share_icon').click(function(){
        $(this).toggleClass('active');
    });
    toggleGraph();
    animationForZilla();
    openCloseRulesBlock();
});
$(window).resize(function(){
    clearTimeout(sliderResizeTimer);
    sliderResizeTimer = setTimeout(initSliderHomepage, 150);
});

function getSliderLayout(width) {
    if (width >= 992) {
        return {maxSlides: 3, slideMargin: 20};
    }

    if (width >= 768) {
        return {maxSlides: 2, slideMargin: 20};
    }

    return {maxSlides: 1, slideMargin: 0};
}

function initSliderHomepage() {
    var $slider = $('.slider');
    if (!$slider.length || typeof $.fn.bxSlider !== 'function') {
        return;
    }

    var nextLayout = getSliderLayout(window.innerWidth);
    var nextLayoutKey = nextLayout.maxSlides + ':' + nextLayout.slideMargin;

    // Не перестворюємо bxSlider, якщо resize не перетнув адаптивний breakpoint.
    if (slider && sliderLayout === nextLayoutKey) {
        if (typeof slider.redrawSlider === 'function') {
            slider.redrawSlider();
        }
        return;
    }

    if (slider) {
        slider.destroySlider();
    }

    slider = $slider.bxSlider({
        slideWidth: 310,
        minSlides: 1,
        maxSlides: nextLayout.maxSlides,
        moveSlides: 1,
        pager: false,
        controls: false,
        slideMargin: nextLayout.slideMargin
    });
    sliderLayout = nextLayoutKey;
}
function hoverGraphBtn(){
    $('.chosen_graph_b').hover(function(){
        $(this).toggleClass('animated_b');
    });
}
function toggleGraph() {
	$('.inner_chosen_graph button').click(function(){
        var graphLink = $(this);
        var pollBlock = graphLink.closest('.poll_block');
        var chartBox = pollBlock.find('.inner_container_graph[data-chart-config]').first();
        var chartWrapper = chartBox.closest('.container_graph');

        // Оновлюємо не лише оформлення, а й доступний стан групи кнопок для допоміжних технологій.
        graphLink.closest('.inner_chosen_graph').find('button').removeClass('active').attr('aria-pressed', 'false');
		graphLink.addClass('active').attr('aria-pressed', 'true');

        // На окремій сторінці опитування фільтри отримують актуальні дані через AJAX.
        // Не малюємо той самий графік удруге зі старими початковими даними.
        if (pollBlock.hasClass('poll_view_page')) {
            chartWrapper.show();
            return;
        }

        if (chartBox.length && typeof renderChart === 'function') {
            try {
                // Малюємо потрібний тип графіка одразу після кліку, навіть якщо користувач не натискав "Побачити результати".
                var chartConfig = JSON.parse(chartBox.attr('data-chart-config'));
                var chartType = graphLink.data('id') || chartConfig.category;

                chartWrapper.show();
                renderChart(chartType, chartConfig.id, chartConfig.title || '', chartConfig.series || [], chartConfig.pie || [], chartConfig.line || {});
                chartBox.data('chart-rendered', true);
            } catch (error) {
                // Якщо JSON пошкоджений, не ламаємо вибір кнопки та лишаємо діагностику для розробника.
                console.error('Не вдалося перемкнути графік опитування:', error);
            }
        }
	});
}
function animationForZilla() {
    if(navigator.userAgent.search(/Firefox/) > -1){
        var elementChosen, elementBg;
        $('.chosen_graph_b span button').each(function(){
            if($(this).hasClass('pie_chart active')) {
                elementBg = 'pie_chart';
                elementChosen = $(this);
                $(this).css({
                    'background-image' : 'none'
                });
            } else if($(this).hasClass('horizontal_b_chart active')) {
                elementBg = 'horizontal_chart';
                elementChosen = $(this);
                $(this).css({
                    'background-image' : 'none'
                });
            } else if ($(this).hasClass('vertical_b_chart active')) {
                elementBg = 'vertical_chart';
                elementChosen = $(this);
                $(this).css({
                    'background-image' : 'none'
                });
            } else if ($(this).hasClass('line_chart active')) {
                // Для нового лінійного типу лишаємо CSS-іконку, бо окремого PNG немає.
                elementBg = null;
                elementChosen = $(this);
            }
        });
        $(".chosen_graph_b").bind("DOMSubtreeModified", function() {
            if(!$(this).hasClass('animated_b')) {
                elementChosen.css({
                    'background-image': elementBg ? 'url(../img/layout/'+elementBg+'_white.png)' : ''
                });
            } else {
                elementChosen.css({
                    'background-image' : 'none'
                });
            }
        });
    }
}
function openCloseRulesBlock() {
    $('#rules-block').click(function(e){
        $('.rules_block').slideDown();
        e.preventDefault();
    });
    $('.top_title_rules a').click(function(e){
        $('.rules_block').slideUp();
        e.preventDefault();
    });
}
