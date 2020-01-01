$(function () {

    'use strict';

    $('.login-page  span').on('click',function () {

        $(this).addClass('active').siblings().removeClass('active');
        $("form").toggleClass('hide');

    });


    /************************************************************************/


    //Hide placeholder when focus and show it when blur(leave the field)
    $('[placeholder]').focus(function () {

        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');

    }).blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));

    });


  /***************************************************************/

    // Confirm meassage before deleting

    $('.confirm').on('click',function () {

       return confirm('Do you really want to delete this ?');

    });


/***************************************************************************** */

    $('.ad-form input').keyup(function () {

        $($(this).data('class')).text($(this).val());

    });






});