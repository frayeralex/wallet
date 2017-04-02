'use strict';

jQuery(function ($) {
    console.log("income page");

    //API urls for ajax request
    const removeIncomeUrl = '/ajax/income/remove';
    const updateIncomeUrl = '/ajax/income/update';


    const incomeItems = $('.income-item');
    const editIncomeModal = $('#editIncome');
    const updateIncomeBtn = $('#updateIncome');
    const removeIncomeBtn = $('#removeIncome');

    incomeItems.on('click', (event)=>{
        const item = $(event.currentTarget);
        const id = item.attr('data-id');
        const title = item.find('.title').text();
        const value = item.find('.value').text();
        const date = item.find('.date').attr('data-date');

        editIncomeModal.attr('data-outcome-id', id);
        editIncomeModal.find('input.title').val(title);
        editIncomeModal.find('input.value').val(parseFloat(value));
        editIncomeModal.find('input.date').val(date.slice(0,10));
        editIncomeModal.modal('show');
    });

    updateIncomeBtn.on('click', ()=>{
        const id = +editIncomeModal.attr('data-outcome-id');
        const title = editIncomeModal.find('input.title').val();
        const value = parseFloat(editIncomeModal.find('input.value').val());
        const createdAt = new Date(editIncomeModal.find('input.date').val()).toISOString();
        const data = {
            id,
            title,
            value,
            createdAt
        };

        $.ajax({
            url: updateIncomeUrl,
            type: 'POST',
            data,
            success: ()=>{
                const item = $(`[data-id="${data.id}"]`);
                item.find('.title').text(data.title);
                item.find('.value').text(data.value);
                item.find('.date').text(moment(data.createdAt).format('MMM D, YYYY'))
                item.find('.date').attr('data-date', data.createdAt);
                editIncomeModal.modal('hide');
            },
            error: ()=>{
                editIncomeModal.modal('hide');
            }
        })
    });

    removeIncomeBtn.on('click',()=>{
        const id = +editIncomeModal.attr('data-outcome-id');
        $.ajax({
            url: removeIncomeUrl,
            type: 'POST',
            data: {id},
            success: ()=>{
                const item = $(`[data-id="${id}"]`);
                item.remove();
                editIncomeModal.modal('hide');
            },
            error: ()=>{
                editIncomeModal.modal('hide');
            }
        })
    });

    console.log("income page")
});
