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
    // Вимикаємо нескінченну прокрутку, щоб bxSlider не додавав клоновані .slide елементи (bx-clone) у DOM.
    // Це зменшує розмір дерева елементів без візуальних змін для користувача.
    var sliderBaseConfig = {
        moveSlides: 1,
        pager: false,
        controls: false,
        infiniteLoop: false,
        hideControlOnEnd: true
    };
    if(width > 991){
        slider = $('.slider').bxSlider($.extend({}, sliderBaseConfig, {
            slideWidth: 310,
            minSlides: 1,
            maxSlides: 3,
            slideMargin: 20
        }));
    } else if ( 768 < width && width < 991){
        slider = $('.slider').bxSlider($.extend({}, sliderBaseConfig, {
            slideWidth: 310,
            minSlides: 1,
            maxSlides: 2,
            slideMargin: 20
        }));
    } else if (width < 768) {
        slider = $('.slider').bxSlider($.extend({}, sliderBaseConfig, {
            slideWidth: 310,
            minSlides: 1,
            maxSlides: 1,
            slideMargin: 0
        }));
    }
}
function hoverGraphBtn(){
    $('.chosen_graph_b').hover(function(){
        $(this).toggleClass('animated_b');
    });
}
function toggleGraph() {
	$('.inner_chosen_graph a').click(function(e){
		$('.inner_chosen_graph a').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
}
function animationForZilla() {
    if(navigator.userAgent.search(/Firefox/) > -1){
    	console.log('asdasdlkjasldk');
        var elementChosen, elementBg;
        $('.chosen_graph_b span a').each(function(){
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
            }
        });
        $(".chosen_graph_b").bind("DOMSubtreeModified", function() {
            if(!$(this).hasClass('animated_b')) {
                elementChosen.css({
                    'background-image': 'url(../img/layout/'+elementBg+'_white.png)'
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
