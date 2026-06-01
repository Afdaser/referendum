console.log('~/referendum.social.local/frontend/web/js/custom.js:v0.02');

window.highchartColors = ['#e0923e','#f5c356','#058f42','#3ac469','#59d9c8','#63b6dd','#238dbf','#726795','#45474d','#b16262'];

$(document).ready(function(){
    try {
        // Робимо невеликий відкладений виклик, щоб нічого не блокувало рендер і preload-стилі.
        setTimeout(function(){
            $('#uLogin div').attr('style','');
            var arr = document.querySelectorAll('[title="Facebook"]');
            $(arr).attr('style','cursor: pointer;');
            $(arr).addClass('social_auth facebook');
            $(arr).prepend('<i class="fa fa-facebook"></i>');
            arr = document.querySelectorAll('[title="VK"]');
            $(arr).attr('style','cursor: pointer;');
            $(arr).addClass('social_auth vk');
            $(arr).prepend('<i class="fa fa-vk"></i>');
            arr = document.querySelectorAll('[title="Twitter"]');
            $(arr).attr('style','cursor: pointer;');
            $(arr).addClass('social_auth twitter');
            $(arr).prepend('<i class="fa fa-twitter"></i>');
            arr = document.querySelectorAll('[title="Google"]');
            $(arr).attr('style','cursor: pointer;');
            $(arr).addClass('social_auth google');
            $(arr).prepend('<i class="fa fa-google-plus"></i>');
        },0);

        // Базова логіка cookie-consent:
        // - зберігаємо вибір у cookie на 180 днів (та дублюємо у localStorage як fallback),
        // - не використовуємо IP для рішення, бо стандартно це прив'язка до браузера/пристрою.
        (function initCookieConsent() {
            var banner = document.querySelector('[data-cookie-consent-banner]');
            if (!banner) {
                return;
            }

            var consentCookieName = 'cookie_consent_choice';
            var consentStorageKey = 'cookie_consent_choice';
            var cookieMaxAgeDays = 180;

            function getCookieValue(name) {
                var escaped = name.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&');
                var match = document.cookie.match(new RegExp('(?:^|; )' + escaped + '=([^;]*)'));
                return match ? decodeURIComponent(match[1]) : '';
            }

            function setCookieValue(name, value, days) {
                var expiresDate = new Date();
                expiresDate.setTime(expiresDate.getTime() + (days * 24 * 60 * 60 * 1000));
                var cookie = name + '=' + encodeURIComponent(value)
                    + '; expires=' + expiresDate.toUTCString()
                    + '; path=/; SameSite=Lax';

                if (location.protocol === 'https:') {
                    cookie += '; Secure';
                }

                document.cookie = cookie;
            }

            function getSavedChoice() {
                var fromCookie = getCookieValue(consentCookieName);
                if (fromCookie) {
                    return fromCookie;
                }

                try {
                    // Не нашкодь:
                    // localStorage без TTL може "заморозити" згоду назавжди.
                    // Тому читаємо тільки формат з expiresAt і очищаємо прострочені/старі значення.
                    var raw = window.localStorage.getItem(consentStorageKey);
                    if (!raw) {
                        return '';
                    }

                    var parsed = JSON.parse(raw);
                    if (!parsed || typeof parsed !== 'object') {
                        window.localStorage.removeItem(consentStorageKey);
                        return '';
                    }

                    var choice = parsed.choice;
                    var expiresAt = Number(parsed.expiresAt || 0);
                    if ((choice !== 'accept' && choice !== 'essential') || !expiresAt) {
                        window.localStorage.removeItem(consentStorageKey);
                        return '';
                    }

                    if (Date.now() > expiresAt) {
                        window.localStorage.removeItem(consentStorageKey);
                        return '';
                    }

                    return choice;
                } catch (storageError) {
                    try {
                        window.localStorage.removeItem(consentStorageKey);
                    } catch (removeError) {
                        // Ігноруємо, бо це лише best-effort очищення.
                    }
                    return '';
                }
            }

            function saveChoice(choice) {
                setCookieValue(consentCookieName, choice, cookieMaxAgeDays);
                try {
                    var expiresAt = Date.now() + (cookieMaxAgeDays * 24 * 60 * 60 * 1000);
                    window.localStorage.setItem(consentStorageKey, JSON.stringify({
                        choice: choice,
                        expiresAt: expiresAt
                    }));
                } catch (storageError) {
                    // Fallback уже є через cookie, тому без додаткових дій.
                }
            }

            function hideBanner() {
                banner.classList.remove('is-visible');
                banner.setAttribute('aria-hidden', 'true');
            }

            function showBanner() {
                banner.classList.add('is-visible');
                banner.removeAttribute('aria-hidden');
            }

            var savedChoice = getSavedChoice();
            if (savedChoice === 'accept' || savedChoice === 'essential') {
                hideBanner();
            } else {
                showBanner();
            }

            banner.addEventListener('click', function (event) {
                var actionButton = event.target.closest('[data-cookie-consent-action]');
                if (!actionButton) {
                    return;
                }

                var action = actionButton.getAttribute('data-cookie-consent-action');
                if (action !== 'accept' && action !== 'essential') {
                    return;
                }

                saveChoice(action);
                hideBanner();
            });
        })();

        window.Share = {
        vkontakte: function(purl, ptitle, pimg, text) {
            var url  = 'http://vkontakte.ru/share.php?';
            url += 'url='          + encodeURIComponent(purl);
            url += '&title='       + encodeURIComponent(ptitle);
            url += '&description=' + encodeURIComponent(text);
            url += '&image='       + encodeURIComponent(pimg);
            url += '&noparse=true';
            Share.popup(url);
        },
        facebook: function(purl, ptitle, pimg, text) {
            var url  = 'http://www.facebook.com/sharer.php?';
            //url += 't='     + encodeURIComponent(ptitle);
            //url += '&p[summary]='   + encodeURIComponent(text);
            //url += '&p[url]='       + encodeURIComponent(purl);
            url += 'u=' + encodeURIComponent(purl);
            Share.popup(url);
        },
        twitter: function(purl, ptitle) {
            var url  = 'http://twitter.com/share?';
            url += 'text='      + encodeURIComponent(ptitle);
            url += '&url='      + encodeURIComponent(purl);
            url += '&counturl=' + encodeURIComponent(purl);
            Share.popup(url);
        },
        gg: function (purl) {
            var url  = 'https://plus.google.com/share?';
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
        var id = $(this).data("id");
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

    // Підтримуємо і нові <button>, і старі посилання коментарів без зміни логіки голосування.
    $('.arrow_rating_top,a.rating_btn_up').click(function(){
        changePollRating($(this).data("id"),1);
    });

    // Підтримуємо і нові <button>, і старі посилання коментарів без зміни логіки голосування.
    $('.arrow_rating_down,a.rating_btn_down').click(function(){
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

    // Логіку створення/редагування опитувань винесено в окремий poll-create-modal.js
    // і підключаємо лише на /site/myPolls.

    $(document).on('click', '.copy_link', function(e){
        e.preventDefault();
        var url = $(this).data('url');
        var $msg = $(this).closest('.right_block_share_icon').find('.copy_link_message');

        var showMessage = function(){
            $msg.fadeIn(200, function(){
                var $self = $(this);
                setTimeout(function(){ $self.fadeOut(200); }, 2000);
            });
        };

        var fallbackCopy = function(){
            var $tmp = $('<textarea readonly class="js-copy-fallback"></textarea>')
                .css({position: 'absolute', left: '-9999px'})
                .val(url)
                .appendTo('body');
            $tmp[0].select();
            try {
                document.execCommand('copy');
            } catch (fallbackError) {
                console.warn('Не вдалося скопіювати текст через execCommand', fallbackError);
            }
            $tmp.remove();
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(showMessage).catch(function(error){
                console.warn('Clipboard API не доступний. Використовуємо запасний варіант.', error);
                fallbackCopy();
                showMessage();
            });
        } else {
            fallbackCopy();
            showMessage();
        }
    });
    } catch (error) {
        console.error('Помилка виконання custom.js. Стилі вже застосовано через preload, але треба перевірити сценарій.', error);
    }
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
