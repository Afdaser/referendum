console.log('~/js/poll-create-modal.js?ver:1.0');

$(document).ready(function () {
    try {
        // Цей скрипт підключається тільки на сторінці /site/myPolls,
        // щоб не тягнути логіку створення опитувань на інші сторінки.

        $('#new_poll_next_step1').click(function () {
            $('#poll_modal_content_step0').hide();
            $('#poll_modal_content_step1').show();
        });

        $('#new_poll_back_step0').click(function () {
            $('#poll_modal_content_step1').hide();
            $('#poll_modal_content_step0').show();
        });

        $('#btn_create_new_poll').click(function () {
            $('#my_profile_all').children('.modal-dialog')
                .load(window.rfrndm.routes.ajax.create_poll_step_one, function (responseTxt, statusTxt, xhr) {
                    if (statusTxt == 'success') {
                        $('#new_poll_next_step1').click(function () {
                            $('#poll_modal_content_step0').hide();
                            $('#poll_modal_content_step1').show();
                        });

                        $('#new_poll_back_step0').click(function () {
                            $('#poll_modal_content_step1').hide();
                            $('#poll_modal_content_step0').show();
                        });

                        $('#poll-form').on('beforeSubmit', function () {
                            var pollForm = $('#poll-form');
                            var pollFormState = false;

                            $.post(window.rfrndm.routes.ajax.poll_form_ajax, pollForm.serialize())
                                .done(function (result) {
                                    if (result.error_message) {
                                        alert(result.error_message);
                                    } else {
                                        alert('Success[1]');
                                    }
                                })
                                .fail(function (result) {
                                    if (result.error_message) {
                                        alert(result.error_message);
                                    }
                                });

                            return pollFormState;
                        });

                        $(document).on('change', '.country', function () {
                            window.refreshRegions($('.country').val(), $('span.region'), 'regionAC', 'region', 'cityAC', $('.city'), 'city');
                            $('#regionAC').val('');
                            $('#region').val(0);
                        });
                    }

                    if (statusTxt == 'error') {
                        alert('Error: ' + xhr.status + ': ' + xhr.statusText);
                    }

                    $('#my_profile_all').modal('show');
                });
        });

        $(document).on('click', '.modal-body .modal_add', function () {
            var url = document.URL.split('.');
            var left = 'Left';
            var chars = 'symbols';

            if (url[0] == 'http://ua') {
                left = 'Залишилось';
                chars = 'символів';
            } else if (url[0] == 'http://ru') {
                left = 'Осталось';
                chars = 'символов';
            }

            $('#new_poll' + $(this).data('id') + ' .item_variants:last').after('<div class="item_variants"><span>' + (parseInt($('#new_poll' + $(this).data('id') + ' .item_variants:last span').text()) + 1) + '</span><input type="text" value="" maxlength="60" class="variant_text answer_var" name="Poll[options][]"><div class="count_symbols">' + left + ': <div class="answer_left">60</div> ' + chars + '</div><a class="del_btn" href="#" data-id="' + $(this).data('id') + '"></a></div>');

            var maxLength = $('.answer_var:last').attr('maxlength');
            $('.answer_var:last').keyup(function () {
                var curLength = $(this).val().length;
                $(this).val($(this).val().substr(0, maxLength));
                var remaning = maxLength - curLength;
                if (remaning < 0) remaning = 0;
                $(this).next().find('.answer_left').html(remaning);
            });
        });

        $(document).on('click', '.item_variants .del_btn', function () {
            $(this).parent().remove();
            var arr = document.querySelectorAll('#new_poll' + $(this).data('id') + ' .item_variants span');
            for (var i = 0; i < arr.length; ++i) {
                $(arr[i]).replaceWith('<span>' + (i + 1) + '</span>');
            }
        });

        $('.answer_var').each(function () {
            var maxLength = $(this).attr('maxlength');
            $(this).keyup(function () {
                var curLength = $(this).val().length;
                $(this).val($(this).val().substr(0, maxLength));
                var remaning = maxLength - curLength;
                if (remaning < 0) remaning = 0;
                $(this).next().find('.answer_left').html(remaning);
            });
        });
    } catch (error) {
        console.error('Помилка в poll-create-modal.js. Ініціалізація створення опитування призупинена.', error);
    }
});

// Глобальну функцію лишаємо сумісною зі старими inline-викликами з PHP-шаблонів.
function refreshChart(popUpNum) {
    function getRandomInt() {
        var min = 1;
        var max = 100;
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    var title = $('#new_poll' + popUpNum + ' #title').val();
    var series = [];
    var pie = [];
    var options = document.querySelectorAll('#new_poll' + popUpNum + ' .variant_text');
    for (var i = 0; i < options.length; ++i) {
        series[i] = { name: $(options[i]).val(), data: [getRandomInt()] };
        pie[i] = [$(options[i]).val(), getRandomInt()];
    }

    renderChart('bar', 'new_poll' + popUpNum + ' #horizontal_b_chart', title, series, pie);
    renderChart('column', 'new_poll' + popUpNum + ' #vertical_b_chart', title, series, pie);
    renderChart('pie', 'new_poll' + popUpNum + ' #pie_chart', title, series, pie);
}
