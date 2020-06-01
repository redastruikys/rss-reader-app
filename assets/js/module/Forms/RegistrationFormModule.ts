import { ApiModule } from '../Api/ApiModule';

const $ = require('jquery');
const $validate = require('validate.js');

interface RegistrationFormModuleInterface {
    init(): void
}

export const RegistrationFormModule: RegistrationFormModuleInterface = {
    init: () => {
        $('form').on('keyup', '.--check-email-on-typing', (event) => {
            const target = $(event.target);
            const email = target.val();
            const validEmail = $validate.single(email, {email: true}) === undefined;

            target.removeClass('is-invalid is-valid');

            if (validEmail) {
                ApiModule.checkEmail(email, (response) => {
                    const { valid, error } = response;
                    console.log([ valid, error ]);

                    if (valid) {
                        target.addClass('is-valid');
                        console.log(target.closest('form'));
                        $('.--form-message-root', target.closest('form'))
                            .removeClass('invalid-feedback')
                            .hide()
                            .find('.--form-message-text')
                            .text('')
                        ;
                    } else {
                        target.addClass('is-invalid');
                        $('.--form-message-root', target.closest('form'))
                            .addClass('invalid-feedback')
                            .show()
                            .find('.--form-message-text')
                            .text(error)
                        ;
                    }
                })
            }
        })
    }
}
