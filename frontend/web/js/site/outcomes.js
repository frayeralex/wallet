'use strict';

jQuery(function ($) {
    //API urls for ajax request
    const removeOutcomeUrl = '/ajax/outcome/remove';
    const updateOutcomeUrl = '/ajax/outcome/update';

    const outcomeItems = $('.outcome-item');
    const editOutcomeModal = $('#editOutcome');
    const updateOutcomeBtn = $('#updateOutcome');
    const removeOutcomeBtn = $('#removeOutcome');

    outcomeItems.on('click', (event)=>{
        const item = $(event.currentTarget);
        const id = item.attr('data-id');
        const title = item.find('.title').text();
        const value = item.find('.value').text();
        const date = item.find('.date').attr('data-date');

        editOutcomeModal.attr('data-outcome-id', id);
        editOutcomeModal.find('input.title').val(title);
        editOutcomeModal.find('input.value').val(parseFloat(value));
        editOutcomeModal.find('input.date').val(date.slice(0,10));
        editOutcomeModal.modal('show');
    });

    updateOutcomeBtn.on('click', ()=>{
        const id = +editOutcomeModal.attr('data-outcome-id');
        const title = editOutcomeModal.find('input.title').val();
        const value = parseFloat(editOutcomeModal.find('input.value').val());
        const createdAt = new Date(editOutcomeModal.find('input.date').val()).toISOString();
        const data = {
            id,
            title,
            value,
            createdAt
        };

        $.ajax({
            url: updateOutcomeUrl,
            type: 'POST',
            data,
            success: ()=>{
                const item = $(`[data-id="${data.id}"]`);
                item.find('.title').text(data.title);
                item.find('.value').text(data.value);
                item.find('.date').text(moment(data.createdAt).format('MMM D, YYYY'))
                item.find('.date').attr('data-date', data.createdAt);
                editOutcomeModal.modal('hide');
            },
            error: ()=>{
                editOutcomeModal.modal('hide');
            }
        })
    });

    removeOutcomeBtn.on('click',()=>{
        const id = +editOutcomeModal.attr('data-outcome-id');
        $.ajax({
            url: removeOutcomeUrl,
            type: 'POST',
            data: {id},
            success: ()=>{
                const item = $(`[data-id="${id}"]`);
                item.remove();

                editOutcomeModal.modal('hide');
            },
            error: ()=>{
                editOutcomeModal.modal('hide');
            }
        })
    });
});
