console.log('~/js/registration-modal.js?ver:1.0');

$(document).ready(function () {
    try {
        // Реєстраційна модалка використовується не лише на /site/myPolls,
        // тому ініціалізуємо її окремим легким скриптом.
        $(document).on('click', '.toggle_modal_login', function (event) {
            // Вхід із бургер-меню відкриваємо у готовій модалці, а не ведемо на службову сторінку /login.html.
            event.preventDefault();
            $(this).closest('details.header_menu').prop('open', false);
            $('#header_login_modal').modal('show');
        });

        $('#header_login_modal').on('shown.bs.modal', function () {
            $('#header-loginform-username').trigger('focus');
        });

        $(document).on('click', '.toggle_modal_registrtion', function (event) {
            // Не даємо службовому href="#" прокручувати сторінку при відкритті модалки.
            event.preventDefault();
            $(this).closest('details.header_menu').prop('open', false);
            $('#header_login_modal').modal('hide');
            $('#registrtion_step_1').children('.modal-dialog')
                .load(window.rfrndm.routes.ajax.registrtion_step_one, function (responseTxt, statusTxt, xhr) {
                    if (statusTxt == 'error') {
                        alert('Error: ' + xhr.status + ': ' + xhr.statusText);
                    }
                    $('#registrtion_step_1').modal('show');
                });
        });

        $(document).on('submit', '#registrtion_step_1 form', function (event) {
            // Реєстрація є модальним flow: помилки показуємо тут, а не відкриваємо endpoint окремою сторінкою.
            event.preventDefault();

            var form = $(this);
            $.post(form.attr('action'), form.serialize()).done(function (result) {
                if (result.redirectUrl) {
                    window.location.assign(result.redirectUrl);
                    return;
                }

                // Сервер повертає лише безпечні текстові повідомлення валідатора.
                alert($.map(result, function (message) { return message; }).join('\n'));
                // Після невдалої перевірки CAPTCHA серверний код уже використаний — оновлюємо зображення.
                form.find('.right_captcha_b img').trigger('click');
            }).fail(function () {
                alert('Не вдалося надіслати форму. Спробуйте ще раз.');
            });
        });
    } catch (error) {
        console.error('Помилка в registration-modal.js. Ініціалізація модалки реєстрації призупинена.', error);
    }
});
