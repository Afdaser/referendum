console.log('~/js/custom-modal.js?ver:0.16');
// http://en.referendum.social.local/js/custom-modal.js
$(document).ready(function () {

    /* NEW POLL: */
    // Code for modal window:
    $('#new_poll_next_step1').click(function () {
        console.log('#DEV2404_A1: Click on #new_poll_next_step1');
        $('#poll_modal_content_step0').hide();
        $('#poll_modal_content_step1').show();
    });

    $('#new_poll_back_step0').click(function () {
        console.log('#DEV2404_A1: Click on #new_poll_back_step0');
        $('#poll_modal_content_step1').hide();
        $('#poll_modal_content_step0').show();
    });
    // Country:
    /*
    $(document).on('change', '.country', function () {
        console.log('Country::refreshRegions-main():');
        window.refreshRegions($('.country').val(), $('span.region'), 'regionAC', 'region', 'cityAC', $('.city'), 'city');
        $('#regionAC').val('');
        $('#region').val(0);
    });
    */
    /* //END NEW POLL */

    $('#btn_create_new_poll').click(function () {
        console.log('#DEV2404_A1: Click on #btn_create_new_poll');

        $('#my_profile_all').children('.modal-dialog')
                .load(window.rfrndm.routes.ajax.create_poll_step_one, function (responseTxt, statusTxt, xhr) {
                    if (statusTxt == "success") {
                        console.log('#DEV2404_A1: External content loaded successfully!');
                        /*
                         function refreshRegions(countryVal,regionClass,regionAC,regionId,cityAC,cityClass,cityId){
                         / * url: '/poll/GetRegions' * /
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
                         /* */
                        // Code for modal window:
                        $('#new_poll_next_step1').click(function () {
                            console.log('#DEV2404_A1: Click on #new_poll_next_step1');
                            $('#poll_modal_content_step0').hide();
                            $('#poll_modal_content_step1').show();
                        });

                        $('#new_poll_back_step0').click(function () {
                            console.log('#DEV2404_A1: Click on #new_poll_back_step0');
                            $('#poll_modal_content_step1').hide();
                            $('#poll_modal_content_step0').show();
                        });

                        $('#poll-form').on('beforeSubmit', function (form) {
                            console.log('#DEV2404_A1:   beforeSubmit#poll-form');
                            var pollForm = $('#poll-form');
                            var pollFormResult = {};
                            var pollFormState = false;

                            $.post(window.rfrndm.routes.ajax.poll_form_ajax, pollForm.serialize())
                                    .done(function (result) {

                                        pollFormResult = result;
                                        if (result.error_message) {
                                            alert(result.error_message);
                                        } else {
                                            alert('Success[1]');
                                            // pollFormState = true;
                                        }
                                        /*
                                         if(result.poll_ok){
                                         if(result.redirect_uri){
                                         window.location.assign(result.redirect_uri);
                                         }
                                         // pollFormState = true;
                                         } /* */
                                    })
                                    .fail(function (result) {
                                        pollFormResult = result;
                                        if (result.error_message) {
                                            alert(result.error_message);
                                        }
                                    });
                            return pollFormState;
                        });
                        // Country:
                        $(document).on('change', '.country', function () {
                            console.log('Country::refreshRegions-main():');
                            window.refreshRegions($('.country').val(), $('span.region'), 'regionAC', 'region', 'cityAC', $('.city'), 'city');
                            $('#regionAC').val('');
                            $('#region').val(0);
                        });
                    }


                    if (statusTxt == "error") {
                        alert("Error: " + xhr.status + ": " + xhr.statusText);
                    }
                    /*
                     $('.input_with_count_symbols').
                     function() {
                     var curLength = $(this).val().length;
                     $(this).val($(this).val().substr(0, maxLength));
                     var remaning = maxLength - curLength;
                     if (remaning < 0) remaning = 0;
                     $(this).next().find('.answer_left').html(remaning);
                     }
                     /* */
                    /*
                     function() {
                     var curLength = $('#answer_text').val().length; //(2)
                     $(this).val($(this).val().substr(0, maxLength)); //(3)
                     var remaning = maxLength - curLength;
                     if (remaning < 0) remaning = 0;
                     $('#textareaFeedback').html(remaning); //(4)
                     if (remaning < 10) //(5)
                     {
                     $('#textareaFeedback').addClass('warning')
                     } else {
                     $('#textareaFeedback').removeClass('warning')
                     }
                     }
                     /* */
                    $('#my_profile_all').modal('show'); //DEV.R#03
//                    $('#registrtion_step_1').modal('show'); //DEV.R#03
                });
    });
    /*
     });
     $(document).ready(function () {
     /* */

//    $('.toggle_modal_registrtion').on
    $('.toggle_modal_registrtion').click(function () {
        $('#registrtion_step_1').children('.modal-dialog')
                .load(window.rfrndm.routes.ajax.registrtion_step_one, function (responseTxt, statusTxt, xhr) {
                    if (statusTxt == "success") {
                        // alert("External content loaded successfully!");
                    }
                    if (statusTxt == "error") {
                        alert("Error: " + xhr.status + ": " + xhr.statusText);
                    }
                    $('#registrtion_step_1').modal('show'); //DEV.R#03
                });

//        alert('modal');
        //$('#registrtion_step_1').children('.modal-dialog').hide(1000);

    });
//    alert('~/js/custom-modal.js');
//    alert($.fn.jquery);
//    alert(jQuery.fn.jquery);
});

/*
 if (typeof jQuery != 'undefined') {
 // jQuery is loaded => print the version
 alert(jQuery.fn.jquery);
 }
 /* */