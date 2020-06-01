import { ApiModule } from '../Api/ApiModule';

const $ = require('jquery');
const $validate = require('validate.js');

interface RegistrationFormModuleInterface {
    init(): void
}

const registration_form_email_errors_id = 'registration_form_email_errors';

const createErrorFeedback = (error: string, id: string) => {
    const template = `
        <span class="invalid-feedback d-block">
            <span class="d-block">
                <span class="form-error-icon badge badge-danger text-uppercase">Error</span> 
                <span class="form-error-message">${error}</span>
            </span>
        </span>
    `;

    return $('<div></div>').attr({ id, class: 'mb-2'}).html(template);
}

export const RegistrationFormModule: RegistrationFormModuleInterface = {
    init: () => {
        $('form').on('keyup', '.--check-email-on-typing', (event) => {
            const target = $(event.target);
            const email = target.val();
            const validEmail = $validate.single(email, {email: true}) === undefined;

            if (validEmail) {
                ApiModule.checkEmail(email, (response) => {
                    const { valid, error } = response;

                    if (valid) {
                        target.addClass('is-valid');
                        $(`#${registration_form_email_errors_id}`).remove();
                    } else {
                        target
                            .removeClass('is-valid')
                            .closest('.form-group')
                            .prepend(createErrorFeedback(error, registration_form_email_errors_id));
                    }
                })
            }
        })
    }
}
