(function ($) {
    "use strict";

    $('html').removeClass('no-js').addClass('js');

    $(window).on('load resize', function () {

        $(".hero-unit.fullscreen .text-container").css('height', $(window).height());

        if ($(window).width() > 750) {
            $('ul.sf-menu').superfish();
        }

        $('ul.sf-menu').slicknav({
            prependTo: 'nav#mob-menu',
            closeOnClick: true,
            label: '',
            allowParentLinks: true,
            openedSymbol: '<i class="fa fa-minus"></i>',
            closedSymbol: '<i class="fa fa-plus"></i>'
        });

        if (!$('nav#mob-menu .slicknav_menu .site-branding').length) {
            $('.site-branding').clone().prependTo('nav#mob-menu .slicknav_menu');
        }

        if ($(window).width() > 480) {


            $('.parallax-bg').each(function () {
                $(this).parallax("50%", $(this).data('parallax-ratio'));
            });
        }


    });

    var disabled = $("#rating").hasClass('posted') ? true : false;

    $('#rating').barrating({
        theme: 'bootstrap-stars',
        readonly: disabled
    });

    $('div[data-method]').hide();

    function toggle_payment_methods(obj) {
        $('div[data-method]').slideUp();

        if (obj.value === 'stripe') {
            loadStripe();
        }

        $('div[data-method="' + obj.value + '"]').slideDown();
    }

    toggle_payment_methods($('input[name="payment_method"]'));

    $(document).on('change', 'input[name="payment_method"]', function () {
        toggle_payment_methods(this);
    });

    function loadStripe() {
        var stripe = Stripe(stripe_key);

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
                $('#card-errors').slideUp();
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            if ($(form).find('input[name="payment_method"]:checked').val() === 'stripe') {
                event.preventDefault();
                $(form).find('button').attr('disabled', 'disabled');
                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        $('#card-errors').slideDown();
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        $(form).find('button').removeAttr('disabled');
                        errorElement.textContent = result.error.message;
                        console.log('here');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            }
        });


        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            console.log(form.submit());
        }
    }

    $(document).on('submit', '#pre-order-form', function (e) {
        e.preventDefault();

        $('#pre-order-form button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
        $('#pre-order-form button[type="submit"]').prop('disabled', true);

        $.ajax({
            method: 'post',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (response) {
                $('#pre-order-form :input').closest('.form-group').hide(300).remove();
                $('#pre-order-form button[type="submit"]').hide(300).remove();

                let status_class = 'success';

                if (!response.status) {
                    status_class = 'danger';
                }

                $('#pre-order-form .modal-body').prepend(`<div class="alert alert-` + status_class + `" role="alert">` + response.message + `</div>`);
            }
        });

    });

    if (logged_in === true) {

        update_notifications();

        function update_notifications() {
            $.ajax({
                url: base_url + '/account/notifications',
                data: {},
                success: function (response) {
                    if ($(response.html).length === 1) {
                        $('.notification-dropdown').empty().append($(response.html).html());
                    }
                },
                complete: function () {
                    // Schedule the next request when the current one's complete
                    setTimeout(update_notifications, 15000); // The interval set to 5 seconds
                }
            });
        }

        $(document).on('click', '.allRead', function (e) {
            e.preventDefault();

            $.ajax({
                url: base_url + '/account/notifications',
                data: {'mark_all_as_read': 1},
                success: function (response) {
                    if ($(response.html).length === 1) {
                        $('.notification-dropdown').empty().append($(response.html).html());
                    }
                },
                complete: function () {
                }
            });
        });

        $(document).on('click', '.clear-notifications', function (e) {
            e.preventDefault();

            $.ajax({
                url: base_url + '/account/notifications',
                data: {'delete_notifications': 1},
                success: function (response) {
                    if ($(response.html).length === 1) {
                        $('.notification-dropdown').empty().append($(response.html).html());
                    }
                },
                complete: function () {
                }
            });
        });
    }

    $(document).on('change', '.user-country', function() {
        var $this = $(this),
            $statesParent = $('.user-state').find('.state-container'),
            $append = '<input class="form-control" placeholder="State" name="usermeta[state]" type="text">';

        get_states($this, $statesParent, $append);

    });


    function get_states($this, $statesParent, $append) {
        $.ajax({
            url: base_url + '/ch-admin/ajax',
            method: 'POST',
            data: {action: 'get_states', country_id: $this.val(), _token: $('meta[name="csrf-token"]').attr('content')},
            success: function( json ){
                json = JSON.parse(json);

                if ( json.length === 0 ) {
                    $statesParent.empty().append($append);
                    return false;
                }

                var source = $("#states-template").html();
                var template = Handlebars.compile(source);
                $statesParent.empty().append(template(json));
            },

            error: function( json ) {

            }
        });
    }
})(jQuery);
