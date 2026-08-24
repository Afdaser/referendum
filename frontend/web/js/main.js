var slider;
$(document).ready(function(){
    // Банер застарілого браузера вимкнено, тож жодних перевірок outdatedBrowser не запускаємо.
    initSliderHomepage();
    hoverGraphBtn();
    $('.slider_next').click(function(e){
        slider.goToNextSlide();
        e.preventDefault();
    });
    $('.slider_prev').click(function(e){
        slider.goToPrevSlide();
        e.preventDefault();
    });
    $('.share_icon').click(function(){
        $(this).toggleClass('active');
    });
    toggleGraph();
    animationForZilla();
    openCloseRulesBlock();
})
$(window).resize(function(){
	if($('.slider').length){		
    	if (slider) {
            slider.destroySlider();
        }
    	initSliderHomepage();
	}
})
function initSliderHomepage() {
    var width = window.innerWidth;
    if(width > 991){
        slider = $('.slider').bxSlider({
            slideWidth: 310,
            minSlides: 1,
            maxSlides: 3,
            moveSlides: 1,
            pager: false,
            controls: false,
            slideMargin: 20
        });
    } else if ( 768 < width && width < 991){
        slider =$('.slider').bxSlider({
            slideWidth: 310,
            minSlides: 1,
            maxSlides: 2,
            moveSlides: 1,
            pager: false,
            controls: false,
            slideMargin: 20
        });
    } else if (width < 768) {
        slider = $('.slider').bxSlider({
            slideWidth: 310,
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            pager: false,
            controls: false,
            slideMargin: 0
        });
    }
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
    	console.log('asdasdlkjasldk');
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
