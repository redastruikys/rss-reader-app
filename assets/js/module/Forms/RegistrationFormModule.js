"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.RegistrationFormModule = void 0;
var ApiModule_1 = require("../Api/ApiModule");
var $ = require('jquery');
var $validate = require('validate.js');
// <div id="registration_form_email_errors" class="mb-2">
//      <span class="invalid-feedback d-block">
//          <span class="d-block">
//              <span class="form-error-icon badge badge-danger text-uppercase">Error</span> <span class="form-error-message">There is already an account with this email</span>
//          </span>
//     </span>
// </div>
var registration_form_email_errors_id = 'registration_form_email_errors';
var createErrorFeedback = function (error, id) {
    var template = "\n        <span class=\"invalid-feedback d-block\">\n            <span class=\"d-block\">\n                <span class=\"form-error-icon badge badge-danger text-uppercase\">Error</span> \n                <span class=\"form-error-message\">" + error + "</span>\n            </span>\n        </span>\n    ";
    // const feedbackBlock = $('<div></div>')
    //     .addClass('invalid-feedback d-block')
    //     .append(
    //         $('<span></span>')
    //             .addClasses('d-block')
    //             .append($('<span></span>').addClass('form-error-icon badge badge-danger text-uppercase').text('Error'))
    //             .append($('<span></span>').addClass('form-error-message').text(error))
    //         );
    return $('<div></div>').attr({ id: id, class: 'mb-2' }).html(template);
};
exports.RegistrationFormModule = {
    init: function () {
        $('form').on('keyup', '.--check-email-on-typing', function (event) {
            var target = $(event.target);
            var email = target.val();
            var validEmail = $validate.single(email, { email: true }) === undefined;
            //target.removeClass('is-invalid is-valid');
            if (validEmail) {
                ApiModule_1.ApiModule.checkEmail(email, function (response) {
                    var valid = response.valid, error = response.error;
                    console.log([valid, error]);
                    if (valid) {
                        target.addClass('is-valid');
                        $("#" + registration_form_email_errors_id).remove();
                        // target.addClass('is-valid');
                        // console.log(target.closest('form'));
                        // $('.--form-message-root', target.closest('form'))
                        //     .removeClass('invalid-feedback')
                        //     .hide()
                        //     .find('.--form-message-text')
                        //     .text('')
                        // ;
                    }
                    else {
                        target
                            .removeClass('is-valid')
                            .closest('.form-group')
                            .prepend(createErrorFeedback(error, registration_form_email_errors_id));
                        /*
                        target.addClass('is-invalid');
                        $('.--form-message-root', target.closest('form'))
                            .addClass('invalid-feedback')
                            .show()
                            .find('.--form-message-text')
                            .text(error)
                        ;
                        */
                    }
                });
            }
        });
    }
};
