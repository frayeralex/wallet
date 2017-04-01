'use strict';

jQuery(function ($) {
    const walletPage = $('.site-wallet');

    walletPage.on('click', '[data-wallet-id]', function (event) {
        const id = event.currentTarget.getAttribute('data-wallet-id');
        $.ajax({
            url: '/site/delete-wallet',
            type: 'POST',
            data: {id: id },
            success: ()=> {
                $(event.currentTarget).parents('tr').remove();
            },
            error: ()=> {

            }
        })
    });
})