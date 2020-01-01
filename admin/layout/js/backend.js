$(function () {

    'use strict';

    //start dashboard
    $(".toggleplusminus").on("click",function () {

        $(this).toggleClass("selected").parent().next(".panel-body").fadeToggle(100);

        if($(this).hasClass("selected"))
        {
            $(this).html('<i class="fas fa-plus-circle pull-right"></i>');
        }
        else
        {
            $(this).html('<i class="fas fa-minus-circle pull-right"></i>');
        }
    });

    //Hide placeholder when focus and show it when blur(leave the field)
    $('[placeholder]').focus(function () {

        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');

    }).blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));

    });

/************************************************************************/

    // Add Asterrisk to the required fields

    $('.req input').each(function () {

        if ($(this).attr('required') === 'required') {

            $(this).after('<span class="asterisk">*</span>');
        }
    });

    /************************************************************************/


    // Show the password when we hover

        var password = $('.password');

        $('.showPass').hover(function () {
            $(this).css("color","#000");
            password.attr('type','text');



        },function ()
          {

             $(this).css("color","#bcbcbc");
             password.attr('type','password');
          }
 );
  /***************************************************************/

    // Confirm meassage before deleting

    $('.confirm').on('click',function () {

       return confirm('Do you really want to delete this ?');

    });
/***************************************************************************** */

$('.cat h3').on('click',function(){
     
    $(this).next('.full-view').fadeToggle(200);
});

/***************************************************************************** */

$('.cats .option span').on('click',function(){
     
    $(this).addClass('active').siblings('span').removeClass('active');

    if($(this).data('view') === 'full')
    {
        $('.cat .full-view').fadeIn(200);
    } else{

        $('.cat .full-view').fadeOut(200);
    }
});


});