require('./bootstrap');

(function ($) {
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $('.meta-wrapper').cloneya({
        valueClone: false
    });
    $('.addon-wrapper').cloneya({
        valueClone: false
    });
    $(document).on('click', '.addon-add-new', function () {
        var parent = $(this).parents('.form-group');
        parent.find('select').hide();
        parent.find('input').show();
    });

    window.Handlebars.registerHelper('select', function (value, options) {
        var $el = $('<select />').html(options.fn(this));
        $el.find('[value="' + value + '"]').attr({'selected': 'selected'});
        return $el.html();
    });

    $(document).on('change', '.tax-country', function () {
        var $this = $(this),
            $statesParent = $this.parents('.toclone').find('.state-container'),
            $append = '<input type="text" class="form-control" id="settings[tax_rates][state][]" name="settings[tax_rates][state][]" value="">';

        get_states($this, $statesParent, $append);

    });

    $(document).on('change', '.user-country', function () {
        var $this = $(this),
            $statesParent = $('.user-state').find('.state-container'),
            $append = '<input class="form-control" placeholder="State" name="usermeta[state]" type="text">';

        get_states($this, $statesParent, $append);

    });

    function get_states($this, $statesParent, $append) {
        $.ajax({
            url: base_url + '/ch-admin/ajax',
            method: 'POST',
            data: {action: 'get_states', country_id: $this.val()},
            success: function (json) {
                json = JSON.parse(json);

                if (json.length === 0) {
                    $statesParent.empty().append($append);
                    return false;
                }

                var source = $("#states-template").html();
                var template = Handlebars.compile(source);
                $statesParent.empty().append(template(json));
            },

            error: function (json) {

            }
        });
    }

    $(document).on('DOMNodeInserted', '.toclone', function (e) {

        if ($('.addon-wrapper').find('.toclone').length === 1) {
            return;
        }

        $(e.target).find('label.control-label').hide();

        $(e.target).find('.state-container').empty().append('<input type="text" class="form-control" id="settings[tax_rates][state][]" name="settings[tax_rates][state][]" value="">');

    });

    update_notifications();

    function update_notifications() {
        $.ajax({
            url: base_url + '/ch-admin/notifications',
            data: {},
            success: function (response) {
                if ($(response.html).length === 1) {
                    $('.notifications.dropdown').empty().append($(response.html).html());
                }
            },
            complete: function () {
                setTimeout(update_notifications, 10000);
            }
        });
    }

    $(document).on('click', '.mark-as-read', function (e) {
        e.preventDefault();
        $.ajax({
            url: base_url + '/ch-admin/notifications',
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
            url: base_url + '/ch-admin/notifications',
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

    $('.datepicker').datepicker({
        dateFormat: "yy-mm-dd"
    });

    $('.sidebar .sidebar-menu li a').on('click', function () {
        const $this = $(this);

        if ($this.parent().hasClass('open')) {
            $this
                .parent()
                .children('.dropdown-menu')
                .slideUp(200, () => {
                    $this.parent().removeClass('open');
                });
        } else {
            $this
                .parent()
                .parent()
                .children('li.open')
                .children('.dropdown-menu')
                .slideUp(200);

            $this
                .parent()
                .parent()
                .children('li.open')
                .children('a')
                .removeClass('open');

            $this
                .parent()
                .parent()
                .children('li.open')
                .removeClass('open');

            $this
                .parent()
                .children('.dropdown-menu')
                .slideDown(200, () => {
                    $this.parent().addClass('open');
                });
        }
    });

    // Sidebar Activity Class
    const sidebarLinks = $('.sidebar').find('.sidebar-link');

    sidebarLinks
        .each((index, el) => {
            $(el).removeClass('active');
        })
        .filter(function () {
            const href = $(this).attr('href');
            const pattern = href[0] === '/' ? href.substr(1) : href;
            return pattern === (window.location.pathname).substr(1);
        })
        .addClass('active');

    // ٍSidebar Toggle
    $('.sidebar-toggle').on('click', e => {
        $('.app').toggleClass('is-collapsed');
        e.preventDefault();
    });

    /**
     * Wait untill sidebar fully toggled (animated in/out)
     * then trigger window resize event in order to recalculate
     * masonry layout widths and gutters.
     */
    $('#sidebar-toggle').click(e => {
        e.preventDefault();
        setTimeout(() => {
            window.dispatchEvent(window.EVENT);
        }, 300);
    });
})(jQuery);

if (document.getElementById('settings[header_code]') != null) {
    CodeMirror.fromTextArea(document.getElementById('settings[header_code]'), {
        lineNumbers: true,
        mode: 'htmlmixed',
    });
}

if (document.getElementById('settings[footer_code]') != null) {
    CodeMirror.fromTextArea(document.getElementById('settings[footer_code]'), {
        lineNumbers: true,
        mode: 'htmlmixed',
    });
}

if (document.getElementById('settings[custom_css]') != null) {
    CodeMirror.fromTextArea(document.getElementById('settings[custom_css]'), {
        lineNumbers: true,
        mode: 'css',
    });
}
