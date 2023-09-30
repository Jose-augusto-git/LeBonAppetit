/**
 *	Copyright (C) 2015-23 CERBER TECH INC., https://wpcerber.com
 */
jQuery( function( $ ) {

    // Async content loading -----------------------------------------------------

    let crb_async_content_areas = $('.crb_async_content');

    if (!__is_empty(crb_async_content_areas)) {
        uis_async_loader(crb_async_content_areas);
    }

    function uis_async_loader(content_areas) {
        (async () => {
            let total_requests = 0;
            //for(element of [1,2,3]){
            for (element of content_areas) {
                let obj = $(element);
                uis_overlay_loader_on(obj);

                total_requests += await uis_async_load_content(obj);

                uis_overlay_loader_off(obj);
                if (total_requests > 100) {
                    alert('Loading is aborted: the limit on the number of AJAX requests has reached.');
                    break;
                }
            }
        })();
    }

    /**
     * Load content for a single object. Makes several requests if necessary.
     *
     * @param jquery_object
     * @returns {Promise<number>}
     */
    async function uis_async_load_content(jquery_object) {
        let crb_done = false;
        let request_num = 0;

        do {
            crb_done = await uis_async_request(jquery_object, request_num);
            request_num++;
        } while (!crb_done);

        return request_num;
    }

    /**
     * Make AJAX call with HTML data attributes that are used as HTTP request fields
     *
     * @param jquery_object
     * @param request_num
     * @returns {Promise<boolean>}
     */
    async function uis_async_request(jquery_object, request_num) {
        let crb_done = true;

        let request_fields = {
            action: 'cerber_ajax',
            ajax_nonce: crb_ajax_nonce,
            request: request_num,
            referrer_page_url : window.location.href
        };

        let data_fields = jquery_object.data();
        if (__is_empty(data_fields)) {
            return true;
        }

        request_fields = {...data_fields, ...request_fields};

        await $.ajax({
            dataType: "json",
            url: ajaxurl,
            data: request_fields,
            beforeSend: function (xhr) {
            }
        })
            .done(function (server_response, textStatus, jqXHR) {
                    if (!__is_empty(server_response.continue)) {
                        crb_done = false;
                    }
                    if (!__is_empty(server_response.html)) {
                        jquery_object.html(server_response.html);
                    }
                    if (!__is_empty(server_response.error)) {
                        alert(server_response.error);
                        crb_done = true;
                    }
                }
            )
            .fail(function (jqXHR, textStatus, errorThrown) {
                let err = errorThrown + ' ' + jqXHR.status;
                //alert(err); // Ctrl + F5 also fires fail event
                console.error('Server Error: ' + err);
            })
            .always(function () {
            })

        return crb_done;
    }

    function uis_overlay_loader_on(area) {
        if (0 === area.find('.uis_loader_wrapper').length) {
            area.append('<div class="uis_loader_wrapper"><div class="uis_page_loader"></div></div>');
        }
        $('.uis_loader_wrapper').show();
    }

    function uis_overlay_loader_off(area) {
        area.find('.uis_loader_wrapper').remove();
        area.addClass('uis_ajax_processed');
    }

    window.uis_loader_remove = function (area) {
        uis_overlay_loader_off(area);
    }

    // Pop-up Dialogs -------------------------------------------------------------------

    $(".crb-popup-dialog-open").on('click', function (event) {

        let popup_element = '#' + $(this).data('popup_element_id');

        $(popup_element + ' .crb-popup-dialog-close').on('click', function () {
            $.magnificPopup.close();
            event.preventDefault();
        });

        $.magnificPopup.open({
            type: 'inline',
            items: {
                src: popup_element
            },
            mainClass: 'crb-popup-dialog-wrap crb-admin-core',
            closeOnContentClick: false,
            preloader: false,
        });

        event.preventDefault();
    });

    $('.crb-popup-dialog form').on('submit', function (event) {

        // This group requires at least one element has to be checked
        let group_id = 'required_min_one';
        let elements = $(this).find(':checkbox[data-validation_group="' + group_id + '"]');
        if (elements.length) {
            let error_msg = $(this).find('#crb-message-' + group_id);
            if (!elements.filter(':checked').length) {
                //elements.filter(':last').parent().after( '<p></p>' );
                error_msg.show().effect('bounce');
                event.preventDefault();
            }
            else {
                error_msg.hide();
            }
        }

    });
});

function __is_empty(thing) {
    if (typeof thing == 'undefined') {
        return true;
    } else if (thing.length === 0) {
        return true;
    }

    return false;
}
