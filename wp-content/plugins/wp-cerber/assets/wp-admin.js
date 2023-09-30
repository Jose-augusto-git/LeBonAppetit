/**
 *    Copyright (C) 2015-23 CERBER TECH INC., https://wpcerber.com
 *
 *    WordPress-specific JavaScript
 *
 */
jQuery( function( $ ) {

    /* Menu editor */

    let the_nav_menu_editor = $('#menu-to-edit');

    function crb_disable_menu_field() {
        the_nav_menu_editor.find('input[value^="*MENU*CERBER*"]').attr('readonly', true);
    }

    the_nav_menu_editor.on('click', 'a.item-edit', function (event) {
        crb_disable_menu_field();
    });

    crb_disable_menu_field();

});
