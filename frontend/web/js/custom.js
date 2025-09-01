console.log('~/referendum.social.local/frontend/web/js/custom.js:v0.02');

highchartColors = ['#e0923e','#f5c356','#058f42','#3ac469','#59d9c8','#63b6dd','#238dbf','#726795','#45474d','#b16262'];

$(document).ready(function(){
    setTimeout(function(){
        $('#uLogin div').attr('style','');
        arr = document.querySelectorAll( '[title="Facebook"]' );
        $(arr).attr('style','cursor: pointer;');
        $(arr).addClass('social_auth facebook');
        $(arr).prepend('<i class="fa fa-facebook"></i>');
        arr = document.querySelectorAll( '[title="VK"]' );
        $(arr).attr('style','cursor: pointer;');
        $(arr).addClass('social_auth vk');
        $(arr).prepend('<i class="fa fa-vk"></i>');
        arr = document.querySelectorAll( '[title="Twitter"]' );
        $(arr).attr('style','cursor: pointer;');
        $(arr).addClass('social_auth twitter');
        $(arr).prepend('<i class="fa fa-twitter"></i>');
        arr = document.querySelectorAll( '[title="Google"]' );
        $(arr).attr('style','cursor: pointer;');
        $(arr).addClass('social_auth google');
        $(arr).prepend('<i class="fa fa-google-plus"></i>');
    },0);

    Share = {
        vkontakte: function(purl, ptitle, pimg, text) {
            url  = 'http://vkontakte.ru/share.php?';
            url += 'url='          + encodeURIComponent(purl);
            url += '&title='       + encodeURIComponent(ptitle);
            url += '&description=' + encodeURIComponent(text);
            url += '&image='       + encodeURIComponent(pimg);
            url += '&noparse=true';
            Share.popup(url);
        },
        facebook: function(purl, ptitle, pimg, text) {
            url  = 'http://www.facebook.com/sharer.php?';
            //url += 't='     + encodeURIComponent(ptitle);
            //url += '&p[summary]='   + encodeURIComponent(text);
            //url += '&p[url]='       + encodeURIComponent(purl);
            url += 'u=' + encodeURIComponent(purl);
            Share.popup(url);
        },
        twitter: function(purl, ptitle) {
            url  = 'http://twitter.com/share?';
            url += 'text='      + encodeURIComponent(ptitle);
            url += '&url='      + encodeURIComponent(purl);
            url += '&counturl=' + encodeURIComponent(purl);
            Share.popup(url);
        },
        gg: function (purl) {
            url  = 'https://plus.google.com/share?';
            url += 'url='          + encodeURIComponent(purl);
            Share.popup(url)
        },

        popup: function(url) {
            window.open(url,'','toolbar=0,status=0,width=626,height=436');
        }
    };

    $("a.rating_btn_up").click(function(){
        changeCommentRating($(this).data("id"),1);
    });

    $("a.rating_btn_down").click(function(){
       changeCommentRating($(this).data("id"),-1);
    });

    function changeCommentRating(id,rating){
        $.ajax({
            type: 'POST',
            url: '/poll/ChangeCommentRating',
            data: {id: id, rating: rating},
            success: function (data) {
                if(data){
                    $('span.rating[data-id="'+id+'"]').html(data);
                }
            }
        });
    }

    $('a.add_answer_btn').click(function(){
        id = $(this).data("id");
        $.ajax({
            type: 'POST',
            url: '/poll/UpAnswerRating',
            data: {id: id},
            success: function (data) {
                if(data){
                    $('div.right_text_count[data-id="'+id+'"]').html(data);
                }
            }
        });
    });

    $('a.arrow_rating_top,a.rating_btn_up').click(function(){
        changePollRating($(this).data("id"),1);
    });

    $('a.arrow_rating_down,a.rating_btn_down').click(function(){
        changePollRating($(this).data("id"),-1);
    });

    function changePollRating(id,rating){
        $.ajax({
            type: 'POST',
            url: '/poll/ChangePollRating',
            data: {id: id, rating: rating},
            success: function (data) {
                if(data){
                    $('span.poll_rating[data-id="'+id+'"]').html(data);
                }
            }
        });
    }

    $(document).on('click', '.tabs_graphs li', function(){
       $('.tabs_graphs li.active input').prop("checked", true);
    });

    $(document).on('click','.modal-body .modal_add',function(){
		var url = document.URL.split('.');
		if(url[0] == 'http://ua'){
			var left = 'Залишилось', chars = 'символів';
		}
		else if(url[0] == 'http://ru'){
			var left = 'Осталось', chars = 'символов';
		}
		else if(url[0] == 'http://en'){
			var left = 'Left', chars = 'symbols';
		}

        $('#new_poll'+$(this).data('id')+" .item_variants:last").after('<div class="item_variants"><span>'+(parseInt($('#new_poll'+$(this).data('id')+" .item_variants:last span").text())+1)+'</span><input type="text" value="" maxlength="60" class="variant_text answer_var" name="Poll[options][]"><div class="count_symbols">' + left + ': <div class="answer_left">60</div> ' + chars + '</div><a class="del_btn" href="#" data-id="'+$(this).data('id')+'"></a></div>');

		$(function(){
			var maxLength = $('.answer_var:last').attr('maxlength');
			$('.answer_var:last').keyup(function()
			{
				var curLength = $(this).val().length;
				$(this).val($(this).val().substr(0, maxLength));
				var remaning = maxLength - curLength;
				if (remaning < 0) remaning = 0;
				$(this).next().find('.answer_left').html(remaning);
			})
		})

	});

    $(document).on('click','.item_variants .del_btn',function(){
       $(this).parent().remove();
        arr = document.querySelectorAll('#new_poll'+$(this).data('id')+' .item_variants span');
        for(var i = 0; i < arr.length; ++i){
            $(arr[i]).replaceWith('<span>'+(i+1)+'</span>');
        }
    });

    $('.answer_var').each(
        function()
        {
            var maxLength = $(this).attr('maxlength'), input = $(this);
            $(this).keyup(function()
            {
                    var curLength = $(this).val().length;
                    $(this).val($(this).val().substr(0, maxLength));
                    var remaning = maxLength - curLength;
                    if (remaning < 0) remaning = 0;
                    $(this).next().find('.answer_left').html(remaning);
            })
        }
    );

    $(document).on('click', '.copy_link', function(e){
        e.preventDefault();
        var url = $(this).data('url');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url);
        } else {
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(url).select();
            document.execCommand('copy');
            $temp.remove();
        }
        var $msg = $(this).closest('.right_block_share_icon').find('.copy_link_message');
        $msg.fadeIn(200, function(){
            var $self = $(this);
            setTimeout(function(){ $self.fadeOut(200); }, 2000);
        });
    });
});

function arrayTotal(data)
{
    var dataSum = 0;
    for (var i=0;i < data.length;i++) {
            dataSum += data[i].data[0];
    }
    return dataSum;
}

function renderChart(category,id,text,series,pie){
    function htmlDecode(value){
        return $('<div/>').html(value).text();
    }

    for(var i in series){
        series[i].name = htmlDecode(series[i].name);
    }

    for(var i in pie){
        pie[i][0] = htmlDecode(pie[i][0]);
    }

    $('#'+id).removeClass('pie').removeClass('bar').removeClass('column');
    $('#'+id).addClass(category);
    if(category == 'bar'){
        $('#'+id).highcharts({
            colors: highchartColors,
            chart: {
                type: 'bar',//column pie bar
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
            },
            title: {
                text: null
            },
            xAxis: {
                categories: [''],
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    text: null
                },
				labels: {
					enabled: false
				}
            },
			tooltip: {
				pointFormat: '{series.name}: {point.y}',
				useHTML: true
			},
			plotOptions: {
				series: {
					pointPadding: 0.02,
					groupPadding: 0.02
				},
				bar: {
					showInLegend: true,
					dataLabels: {
						enabled: true,
						formatter:function() {
							var pcnt = (this.y / arrayTotal(series)) * 100;
							return '<b>'+Highcharts.numberFormat(pcnt) + ' %</b><br>'+this.y;
						}
					}

				}
			},
            credits: {
                enabled: false
            },
            series: series
        });
    } else if(category == 'column'){
        $('#'+id).highcharts({
            colors: highchartColors,
            chart: {
                type: 'column'//column pie bar
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['']
            },
            yAxis: {
                title: {
                    text: null
                },
				labels: {
					enabled: false
				}
			},
			tooltip: {
				pointFormat: '{series.name}: {point.y}',
				useHTML: true
			},
			plotOptions: {
				series: {
					pointPadding: 0.02,
					groupPadding: 0.02
				},
				column: {
					showInLegend: true,
					dataLabels: {
						enabled: true,
						formatter:function() {
							var pcnt = (this.y / arrayTotal(series)) * 100;
							return '<b>'+Highcharts.numberFormat(pcnt) + ' %</b><br>'+this.y;
						}
					}

				}
			},
            credits: {
                enabled: false
            },
            series: series
        });
    } else {
        $('#'+id).highcharts({
            colors: highchartColors,
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
					showInLegend: true,
					allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
						format: '<b>{point.percentage:.1f}%</b><br>{point.y}',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                type: 'pie',
                tooltip: {pointFormat:'{point.percentage:.1f}%'},
                data: pie
            }]
        });
    }

}

function filterDataChart(id,title){
    gender = $('.gender').val();
    age = $('.age').val();
    country = $('.country').val();
    // region = $('#region').val();
    registration = $('.registration').val() || 0;

    /* url: '/poll/GetChartData' */
    $.ajax({
        type: 'POST',
        url: '/poll/ajax/get-chart-data',
        data: {gender: gender, age: age, country: country, registration: registration, id: id},
        success: function (data) {
            data = JSON.parse(data);
            for(i in data.bar.series){
                data.bar.series[i].data[0] = parseInt(data.bar.series[i].data[0]);
            }

            if($('#container'+id).hasClass('pie')){
                renderChart('pie','container'+id,title,data.bar.series,data.pie);
            } else if($('#container'+id).hasClass('bar')){
                renderChart('bar','container'+id,title,data.bar.series,data.pie);
            } else {
                renderChart('column','container'+id,title,data.bar.series,data.pie);
            }
        }
    });
}

function refreshRegions(countryVal,regionClass,regionAC,regionId,cityAC,cityClass,cityId){
    /* url: '/poll/GetRegions' */
    $.ajax({
        type: 'POST',
        url: '/poll/ajax/get-regions',
        data: {country: countryVal},
        success: function (data) {
            //data = JSON.parse(data);

            console.log('#DEV2404_A2: refreshRegions::success()');
            $('#'+regionAC).autocomplete({
                appendTo: regionClass,
                lookup: data,
                onSelect: function(ui) {
                    console.log('#DEV2404_A3: refreshRegions::success() => onSelect::fx()');
                    $('#'+regionId).val(ui.data);
                    refreshCities(countryVal,ui.data,cityAC,cityClass,cityId);
                }
            });
        }
    });
}

function getAllRegions(regionClass,regionAC,regionId){
/* url: '/poll/GetRegions' */
    $.ajax({
        type: 'POST',
        url: '/poll/ajax/get-regions',
        data: {country: 0},
        success: function (data) {
            //data = JSON.parse(data);

            $('#'+regionAC).autocomplete({
                appendTo: regionClass,
                lookup: data,
                onSelect: function(ui) {
                    $('#'+regionId).val(ui.data);
                }
            });
        }
    });
}

function refreshCities(countryVal,regionVal,cityAC,cityClass,cityId){
/* url: '/poll/GetCities', */
    $.ajax({
        type: 'POST',
        url: '/poll/ajax/get-cities',
        data: {country: countryVal,region: regionVal},
        success: function (data) {
//            data = JSON.parse(data);

            $('#'+cityAC).autocomplete({
                appendTo: cityClass,
                lookup: data,
                onSelect: function(ui) {
                    $('#'+cityId).val(ui.data);
                }
            });
        }
    });
}

function getRandomInt(){
    min = 1;
    max = 100;
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function refreshChart(popUpNum) {
    title = $('#new_poll' + popUpNum + ' #title').val();
    series = [];
    pie = [];
    options = document.querySelectorAll('#new_poll' + popUpNum + ' .variant_text');
    for(var i = 0; i < options.length; ++i){
        series[i] = {name: $(options[i]).val(), data: [getRandomInt()]};
        pie[i] = [$(options[i]).val(),getRandomInt()];
    }

    renderChart('bar','new_poll'+popUpNum+' #horizontal_b_chart',title,series,pie);
    renderChart('column','new_poll'+popUpNum+' #vertical_b_chart',title,series,pie);
    renderChart('pie','new_poll'+popUpNum+' #pie_chart',title,series,pie);
}

function clearChartFilters(id,title){
    $('.gender option:first, .age option:first, .country option:first, .registration option:first').prop('selected',true);
    //$('#region, #regionAC').val('');
    filterDataChart(id,title);
}

$(document).on('change','#login',function(){
    $.ajax({
        type: 'POST',
        url: '/site/checkLogin',
        data: {login: $(this).val()},
        success: function (data) {
            if(data == 1){
                $('#login').after('<a href="#" class="del_btn active"></a>');
            } else {
                $('.del_btn.active').remove();
            }
        }
    });
});

$(document).on('click','.email .del_btn', function(){
    $('#email').val('');
});

$(document).on('click','.clearComments',function(){
   id = $(this).data("id");
    $.ajax({
        type: 'POST',
        url: '/user/readComments',
        data: {id: id},
        success: function (data) {
            if(data == 1){
                $('.poll'+id).remove();
            }
        }
    });
});

$(document).on('click','.clearAllComments',function(){
    $.ajax({
        type: 'POST',
        url: '/user/readAllComments',
        success: function (data) {
            if(data == 1){
                $('.item_answer').remove();
            }
        }
    });
});

$(document).on('click','.clearAnswers',function(){
    id = $(this).data("id");
    $.ajax({
        type: 'POST',
        url: '/user/readAnswers',
        data: {id: id},
        success: function (data) {
            if(data == 1){
                $('.comment'+id).remove();
            }
        }
    });
});

$(document).on('click','.clearAllAnswers',function(){
    $.ajax({
        type: 'POST',
        url: '/user/readAllAnswers',
        success: function (data) {
            if(data == 1){
                $('.item_answer').remove();
            }
        }
    });
});

$(document).on('click','.radio_open_vote', function(){
    $('.for_close_radio').hide();
});

$(document).on('click','.radio_close_vote', function(){
    $('.for_close_radio').show();
});

$(document).on('click','.region .del_btn',function(){
    $('#' + $(this).data('id') + ' #regionPoll').val(0);
    $('#' + $(this).data('id') + ' #regionACPoll').val('');
    $('#' + $(this).data('id') + ' #cityPoll').val(0);
    $('#' + $(this).data('id') + ' #cityACPoll').val('');
});

$(document).on('click','.city .del_btn',function(){
    $('#' + $(this).data('id') + ' #cityPoll').val(0);
    $('#' + $(this).data('id') + ' #cityACPoll').val('');
});

$(document).on('click','#registrationCancel',function(){
   $('#registrationBody input').val('');
   $('#registrationBody #agreeTerms').attr("checked", false);
    $('.del_btn.active').remove();
});

$(document).on('click','.newPollCancel',function(){
   document.location.href = document.location.href;
});

function getDaysCount(year,month){
    return 33 - new Date(year, month-1, 33).getDate();
}

$(document).on('change','#my_profile_main .month_birth,#my_profile_main .year_birth',function(){
    $('.day_birth option').remove();
    days = getDaysCount($('#my_profile_main .year_birth').val(),$('#my_profile_main .month_birth').val());
    for(i=1;i<=days;i++){
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        $('#my_profile_main .day_birth').append(opt);
    }
});

$(document).on('change','#my_profile_main .month_birth,#my_profile_main .year_birth',function(){
    $('.day_birth option').remove();
    days = getDaysCount($('#my_profile_main .year_birth').val(),$('#my_profile_main .month_birth').val());
    for(i=1;i<=days;i++){
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        $('#my_profile_main .day_birth').append(opt);
    }
});

$(document).on('change','#profile_main .month_birth,#profile_main .year_birth',function(){
    $('.day_birth option').remove();
    days = getDaysCount($('#profile_main .year_birth').val(),$('#profile_main .month_birth').val());
    for(i=1;i<=days;i++){
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        $('#profile_main .day_birth').append(opt);
    }
});
