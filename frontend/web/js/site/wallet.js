'use strict';

jQuery(function ($) {
    //API urls for ajax request
    const removeWalletUrl = '/ajax/wallet/remove';
    const updateWalletUrl = '/ajax/wallet/update';

    const walletItems = $('.wallet-item');
    const editWalletModal = $('#editWallet');
    const updateWalletBtn = $('#updateWallet');
    const removeWalletBtn = $('#removeWallet');


    walletItems.on('click', (event)=>{
        const item = $(event.currentTarget);
        const id = item.attr('data-id');
        const name = item.find('.name').text();
        const value = item.find('.value').text();

        editWalletModal.attr('data-wallet-id', id);
        editWalletModal.find('input.name').val(name);
        editWalletModal.find('input.value').val(parseFloat(value));
        editWalletModal.modal('show');
    });

    updateWalletBtn.on('click', ()=>{
        const id = editWalletModal.attr('data-wallet-id');
        const name = editWalletModal.find('input.name').val();
        const value = editWalletModal.find('input.value').val();
        const data = {
            id,
            name,
            value,
        };

        console.log("data", data)

        $.ajax({
            url: updateWalletUrl,
            type: 'POST',
            data,
            success: ()=>{
                const item = $(`[data-id="${data.id}"]`);
                item.find('.name').text(data.title);
                item.find('.value').text(data.value);
                editWalletModal.modal('hide');
            },
            error: ()=>{
                editWalletModal.modal('hide');
            }
        })
    });

    removeWalletBtn.on('click',()=>{
        const id = editWalletModal.attr('data-wallet-id');
        $.ajax({
            url: removeWalletUrl,
            type: 'POST',
            data: {id},
            success: ()=>{
                const item = $(`[data-id="${id}"]`);
                item.remove();
                editWalletModal.modal('hide');
            },
            error: ()=>{
                editWalletModal.modal('hide');
            }
        })
    });

    /*
    * Wallet transaction form
    * */

    const transactionForm = $('#walletTransaction form');
    const transactionModal = $('#walletTransaction');
    const selectFrom = $('#wallet_from');
    const selectTo = $('#wallet_to');
    const valueFrom = $('#value_from');
    const valueRate = $('#value_rate');
    const valueTo = $('#value_to');

    function getSelectValue(select) {
        return select.find(`[value="${select.val()}"]`).attr("data-value")
    }
    function getSelectOption(select) {
        return select.find(`[value="${select.val()}"]`)
    }

    //init values
    const optionValues = $.map($(`${selectTo.selector} option`), item=>item.value);
    let valFrom = +getSelectValue(selectFrom);
    let valTo = +getSelectValue(selectTo);
    let rate = 1;

    valueFrom.val(valFrom);
    valueRate.val(rate);

    function checkWalletConflict() {
        if(selectFrom.val() === selectTo.val()){
            const value = selectTo.val();
            $(`${selectTo.selector} option`).attr('disabled', false);
            getSelectOption(selectTo).attr('disabled', true);
            selectTo.val(optionValues.find(item=>item!==value));
        }
    }

    function updateCalculate() {
        if(isNaN(rate) || isNaN(valFrom)) return;
        valueTo.val(((valFrom * rate) + valTo).toFixed(2));
    }
    checkWalletConflict();
    updateCalculate();

    function changeValue(event) {
        valFrom = event.target.value !== 0 ? parseFloat(event.target.value): 0;
        updateCalculate();
    }

    function changeRate(event) {
        rate = event.target.value !== 0 ? parseFloat(event.target.value) : 0;
        updateCalculate();
    }

    function selectToCb() {
        checkWalletConflict();
        valTo = parseFloat(getSelectValue(selectTo));
        updateCalculate();
    }

    function selectFromCb() {
        checkWalletConflict();
        valFrom = parseFloat(getSelectValue(selectFrom));
        updateCalculate();
        valueFrom.val(valFrom);
    }

    function transactionFormCb(event) {
        event.preventDefault();

        $.ajax({
            url: '/ajax/wallet/transaction',
            type: 'POST',
            data: {
                from: selectFrom.val(),
                to: selectTo.val(),
                valFrom,
                rate
            },
            success: (res)=> {
                transactionModal.modal('hide');
                $(`tr[data="${selectFrom.val()}"] .value`).text(+getSelectValue(selectFrom) - valFrom);
                $(`tr[data="${selectTo.val()}"] .value`).text(valTo);
            },
            error: ()=> {
                transactionModal.modal('hide');
            }
        })
    }

    valueFrom.on('input', changeValue);
    valueRate.on('input', changeRate);
    selectFrom.on('change', selectFromCb);
    selectTo.on('change', selectToCb);
    transactionForm.on('submit', transactionFormCb)
});