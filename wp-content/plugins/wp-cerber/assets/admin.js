/**
 *	Copyright (C) 2015-23 CERBER TECH INC., https://wpcerber.com
 */
jQuery( function( $ ) {

    let crb_admin = $('#crb-admin');

    /* Select2 */

    let crb_se2 = crb_admin.find('select.crb-select2-ajax');
    if (crb_se2.length) {
        crb_se2.select2({
            allowClear: true,
            placeholder: crb_se2.data( 'placeholder' ),
            minimumInputLength: crb_se2.data('min_symbols') ? crb_se2.data('min_symbols') : '1',
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        user_search: params.term,
                        action: 'cerber_ajax',
                        ajax_nonce: crb_ajax_nonce,
                    };
                },
                processResults: function( data ) {
                    return {
                        results: data
                    };
                },
                // cache: true // doesn't work due to "no-cache" header, see also: https://github.com/select2/select2/issues/3862
            }
        });
    }

    crb_se2 = crb_admin.find('select.crb-select2');
    if (crb_se2.length) {
        crb_se2.select2({
            /*width: 'resolve',*/
            /*selectOnClose: true*/
        });
    }

    crb_se2 = crb_admin.find('select.crb-select2-tags');
    if (crb_se2.length) {
        crb_se2.select2({
            tags: true,
            allowClear: true
        });
    }

    /* UI utils */

    crb_admin.on('click', '.crb-opener', function (event) {
        let target = $(this).data('target');
        if (target) {
            $('#'+target).slideToggle(200);
        }
    });


    /* WP Comments page */
    let comtable = 'table.wp-list-table.comments';

    if (typeof crb_lab_available !== 'undefined' && crb_lab_available && $(comtable).length) {
        $(comtable + " td.column-author").each(function (index) {
            let ip = $(this).find('a').last().text();
            let ip_id = cerber_get_id_ip(ip);
            $(this).append('<p><img class="crb-ajax-load" data-ajax_group="country" data-item_id="' + ip_id + '" src="' + crb_ajax_loader + '" /></p>');
        });
    }

    /* Load IP address data with AJAX */

    // New

    window.ajax_items = $(".crb-ajax-load");

    if (ajax_items.length) {
        cerber_ajax_data_process(ajax_items);
    }

    function cerber_ajax_data_process(ajax_items) {
        let ajax_groups = [];
        let group_items = [];

        ajax_items.each(function (index) {

            // Skip hidden elements. This class is used by WordPress to hide columns in the WordPress tables
            if ($(this).parent('.hidden').length) {
                $(this).replaceWith('');
                return;
            }

            let group = $(this).data('ajax_group');
            if (crb_is_empty(group_items[group])) {
                group_items[group] = [];
            }
            group_items[group].push(this);
            ajax_groups.push(group);
        });

        let ajax_groups_unique = ajax_groups.filter((element, index) => {
            return ajax_groups.indexOf(element) === index;
        });

        ajax_groups_unique.forEach(function (group) {
            let ajax_list = [];
            group_items[group].forEach(function (item) {
                let item_id = $(item).data('item_id');
                if (!crb_is_empty(group_items[group])) {
                    ajax_list.push(item_id);
                }
            });

            if (ajax_list.length !== 0) {
                $.post(ajaxurl, {
                    action: 'cerber_ajax',
                    crb_ajax_slug: group,
                    crb_ajax_list: ajax_list,
                    ajax_nonce: crb_ajax_nonce
                }, cerber_ajax_data_set, 'json');
            }
        });
    }

    function cerber_ajax_data_set(server_response) {
        if (crb_is_empty(server_response['data'])) {
            console.log('Error: No data provided by the server.');
            return;
        }
        let data = server_response['data'];
        let group = server_response['slug'];

        ajax_items.filter('[data-ajax_group="' + group + '"]').each(function () {
            $(this).replaceWith(data[$(this).data('item_id')]);
        });
    }

    // ACL management

    $(".acl-table .delete_entry").on('click', function () {
        /* if (!confirm('<?php _e('Are you sure?','wp-cerber') ?>')) return; */
        $.post(ajaxurl, {
                action: 'cerber_ajax',
                acl_delete: $(this).data('ip'),
                slice: $(this).closest('[data-acl-slice]').data('acl-slice'),
                ajax_nonce: crb_ajax_nonce
            },
            onDeleteResponse,
            'json'
        );
        /*$(this).parent().parent().fadeOut(500);*/
        /* $(this).closest("tr").FadeOut(500); */
    });

    function onDeleteResponse(server_response) {
        if (!crb_is_empty(server_response.error)) {
            alert(server_response.error);
        }
        else {
            $('.delete_entry[data-ip="' + server_response.deleted_ip + '"]').parent().parent().fadeOut(300);
        }
    }

    // ----------------------

    $(".cerber-dismiss").on('click', function () {
        $(this).closest('.cerber-msg').fadeOut(500);

        $.get(ajaxurl, {
                action: 'cerber_ajax',
                ajax_nonce: crb_ajax_nonce,
                dismiss_info: 1,
                button_id: $(this).attr('id'),
            }
        );
    });

    $(".crb-notice-dismiss").on('click', function () {
        $(this).closest('div').fadeOut(300);
    });

    function cerber_get_id_ip(ip) {
        let id = ip.replace(/\./g, '-');
        id = id.replace(/:/g, '_');

        return id;
    }

    /* Traffic */

    let crb_traffic = $('#crb-traffic');

    crb_traffic.find('tr.crb-toggle td.crb-request').on('click', function (event) {
        //alert(event.target.tagName);
        if ($(event.target).data('no-js') === 1) {
            return;
        }
        let request_details = $(this).parent().next();
        request_details.toggle();
    });

    let crb_traffic_tr = crb_traffic.find('tr');

    crb_traffic_tr.on('mouseenter', function () {
        $(this).find('a.crb-traffic-more').css('left', '0');
    });

    crb_traffic_tr.on('mouseleave', function () {
        $(this).find('a.crb-traffic-more').css('left', '-9999em');
    });

    $('#traffic-search-btn').on('click', function (event) {
        $('#crb-traffic-search').slideToggle(500);
    });

    /* Enabling conditional input setting fields */

    let setting_form = $('.crb-settings');

    setting_form.find('input,select').on('change', function () {
        let enabler_id = $(this).attr('id');
        let enabler_val;

        if ('checkbox' === $(this).attr('type')) {
            enabler_val = !!$(this).is(':checked');
        }
        else {
            enabler_val = $(this).val();
        }

        setting_form.find('[data-input_enabler="' + enabler_id + '"]').each(function () {
            let input_data = $(this).data();
            let method = 'hide';

            if (typeof input_data['input_enabler_value'] !== "undefined") {
                let target = input_data['input_enabler_value'];
                if (Array.isArray(target)) {
                    for (let i = 0; i < target.length; i++) {
                        if (String(enabler_val) === String(target[i])) {
                            method = 'show';
                            break;
                        }
                    }
                }
                else {
                    if (String(enabler_val) === String(input_data['input_enabler_value'])) {
                        method = 'show';
                    }
                }
            }
            else {
                if (enabler_val) {
                    method = 'show';
                }
            }

            let input_wrapper = $(this).closest('tr');

            if (method === 'show') {
                input_wrapper.fadeIn(500);
                input_wrapper.find('input[data-input_required]').prop('required', true);
            }
            else if (method === 'hide') {
                input_wrapper.fadeOut();
                input_wrapper.find('input[data-input_required]').prop('required', false);
            }

        });
    });

    // Add UTM

    $('div#crb-admin').on('click', 'a', function (event) {
        let link = $(this).attr('href');
        if (link.startsWith('https://wpcerber.com') && !link.includes('wp-admin')) {
            let url_char = '?';
            if (link.includes('?')) {
                url_char = '&';
            }
            $(this).attr('href', link + url_char + 'utm_source=wp_plugin&culoc=' + crb_user_locale);
        }
    });

    /* Nexus Master's code */

    $('#crb-nexus-sites .crb-nexus-managed .column-updates a').on('click', function (event) {
        let managed_site_id = $(this).closest('tr').data('managed-site-id');
        let managed_site_name = $(this).closest('tr').data('managed-site-name');

        $.magnificPopup.open({
            items: {
                src: ajaxurl + '?managed_site_id=' + managed_site_id + '&action=cerber_master_ajax&crb_ajax_do=nexus_view_updates&ajax_nonce=' + crb_ajax_nonce,
            },
            type: 'ajax',
            callbacks: {
                parseAjax: function (server_response) {
                    let the_response = JSON.parse(server_response.data);
                    // Note: All html MUST BE inside of "crb-popup-wrap"
                    server_response.data = '<div id="crb-popup-wrap"><div id="crb-outer"><div id="crb-inner"><h3>' + the_response['header'] + ' ' + managed_site_name + '</h3>' + the_response['html'] + '</div></div><p class="crb-popup-controls"><input type="button" value="OK" class="crb-mpopup-close button button-primary"></p></div>';
                },
                ajaxContentAdded: function() {
                    let popup_width =  window.innerWidth * ((window.innerWidth < 800) ? 0.7 : 0.6);
                    $('.crb-admin-mpopup .mfp-content').css('width', popup_width + 'px');
                    let popup_height = window.innerHeight * ((window.innerHeight < 800) ? 0.7 : 0.6);
                    $('.crb-admin-mpopup #crb-inner').css('max-height', popup_height + 'px');
                }
            },
            overflowY: 'scroll', // main browser scrollbar
            mainClass: 'crb-admin-mpopup',
            closeOnContentClick: false,
            //preloader: true,
        });

        event.preventDefault();
    });

    $(document.body).on('click', '.crb-mpopup-close', function (event) {
        $.magnificPopup.close();
        event.preventDefault();
    });

    // GEO

    $("form#crb-geo-rules .crb-geo-switcher").on('change', function () {
        let to_show = '#crb-geo-wrap_' + $(this).data('rule-id');
        if ($(this).val() !== '---first') {
            to_show += '_' + $(this).val()
        }
        $(to_show).parent().children('.crb-geo-wrapper').hide();
        $(to_show).show();
    });

    // Simple Highlighter

    // Search and highlighting pieces of text, case-sensitive
    function cerber_highlight_text(id, text, limit) {
        let inputText = document.getElementById(id);
        if (inputText === null) {
            return;
        }

        let innerHTML = inputText.innerHTML;
        let i = 0;
        let list = [];
        let index = innerHTML.indexOf(text);
        while (index >= 0 && i < limit) {
            list.push(index);
            index = innerHTML.indexOf(text, index + 1);
            i++;
        }
        list.reverse();
        list.forEach(function (index) {
            innerHTML = innerHTML.substring(0, index) + "<span class='cerber-error'>" + innerHTML.substring(index, index + text.length) + "</span>" + innerHTML.substring(index + text.length);
        });

        inputText.innerHTML = innerHTML;
    }

    cerber_highlight_text('crb-log-viewer', 'ERROR:', 200);



    /* VTabs */

    // Initialize the first tab
    let form_id = $('#crb-vtabs').closest('form').attr('id');
    let vac = crb_get_local('vtab_active' + form_id);
    if (vac) {
        $('#crb-vtabs [data-tab-id=' + vac + ']').addClass('active_tab');
    }
    else {
        $('#crb-vtabs .tablinks').first().addClass('active_tab');
    }

    crb_init_active_tab();

    function crb_init_active_tab() {
        let active = $('#crb-vtabs .active_tab');
        let callback = active.data('callback');
        let tab_id = active.data('tab-id');
        $('#tab-' + tab_id).show();
        if (callback && (typeof window[callback] === "function")) {
            window[callback](tab_id);
        }
    }

    $('.tablinks').on('click', function () {
        let tab_id = $(this).data('tab-id');
        $('.vtabcontent').hide();
        //$('#tab-' + tab_id).show();

        $(".tablinks").removeClass('active_tab');
        $(this).addClass("active_tab");

        crb_init_active_tab();
        crb_update_local('vtab_active' + form_id, tab_id);
    });

});

/* Storage API */

const crb_sprefix = 'wp_cerber_';

function crb_update_local(key, value, json = false) {
    if (json) {
        value = JSON.stringify(value)
    }

    localStorage.setItem(crb_sprefix + key, value);
}

function crb_get_local(key, json = false) {
    let value = localStorage.getItem(crb_sprefix + key);

    if (!json) {
        if (value == null) {
            value = '';
        }
        return value;
    }

    if (value == null || value == '') {
        return {};
    }

    return JSON.parse(value);
}

function crb_delete_local(key) {
    localStorage.removeItem(crb_sprefix + key);
}

/* Misc */

function crb_is_empty(thing) {
    if (typeof thing === 'undefined') {
        return true;
    } else if (thing.length === 0) {
        return true;
    }

    return false;
}
