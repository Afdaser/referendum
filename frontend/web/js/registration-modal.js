console.log('~/js/registration-modal.js?ver:1.0');

$(document).ready(function () {
    try {
        // Реєстраційна модалка використовується не лише на /site/myPolls,
        // тому ініціалізуємо її окремим легким скриптом.
        $('.toggle_modal_registrtion').click(function (event) {
            // Не даємо службовому href="#" прокручувати сторінку при відкритті модалки.
            event.preventDefault();
            $('#registrtion_step_1').children('.modal-dialog')
                .load(window.rfrndm.routes.ajax.registrtion_step_one, function (responseTxt, statusTxt, xhr) {
                    if (statusTxt == 'error') {
                        alert('Error: ' + xhr.status + ': ' + xhr.statusText);
                    }
                    $('#registrtion_step_1').modal('show');
                });
        });
    } catch (error) {
        console.error('Помилка в registration-modal.js. Ініціалізація модалки реєстрації призупинена.', error);
    }
});
