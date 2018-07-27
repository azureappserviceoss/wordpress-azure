/**
 * Created with JetBrains PhpStorm.
 * User: Max
 * Date: 04/07/13
 * Time: 11:35 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {

    //表单验证 <http://jqueryvalidation.org/documentation/>
    $('#baoming-form').validate({
        rules: {
            name: {
                required: true
            },
            age: {
                required: true,
                number: true
            },
            phone: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            address: {
                required: true,
                maxlength: 100
            },
            level: {
                required: true
            },
            site: {
                required: true
            },
            legal_info: {
                required: true
            },
            emergency_name: {
                required: true
            },
            emergency_phone: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please fill in your name."
            },
            age: {
                required: "Please fill in your age.",
                number: "The age should be numeric."
            },
            phone: {
                required: "Please fill in your phone number."
            },
            email: {
                required: "Please fill in your email address.",
                email: 'The format of your email address is invalid.'
            },
            address: {
                required: 'Please fill in your address.',
                maxlength: 'The length of your address should less than 100 characters.'
            },
            level: {
                required: 'Please choose your skill level.'
            },
            site: {
                required: 'Please choose the place for Training Program.'
            },
            legal_info: {
                required: 'Please read and accept the terms and conditions.'
            },
            emergency_name: {
                required: "Please fill in the emergency contact."
            },
            emergency_phone: {
                required: "Please fill in the phone number of the emergency contact."
            }
        },
        errorElement: 'em',
        errorPlacement: function(error, element) {
            element.parent().find('.display_error_here').append(error);
        }
    });

});