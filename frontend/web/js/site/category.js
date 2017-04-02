'use strict';

jQuery(function ($) {
    console.log("category page");
    //API urls for ajax request
    const removeCategoryUrl = '/ajax/category/remove';
    const updateCategoryUrl = '/ajax/category/update';


    const categoryItems = $('.category-item');
    const editCategoryModal = $('#editCategory');
    const updateCategoryBtn = editCategoryModal.find('#updateCategory');
    const removeCategoryBtn = editCategoryModal.find('#removeCategory');

    categoryItems.on('click', (event)=>{
        const categoryItem = $(event.currentTarget);
        const categoryId = categoryItem.attr('data-catagory-id');
        const categoryName = categoryItem.find('.name').text();

        editCategoryModal.find('input.category-name').val(categoryName);
        editCategoryModal.attr('data-category', categoryId);
        editCategoryModal.modal('show');
    });

    updateCategoryBtn.on('click', ()=>{
        const id = editCategoryModal.attr('data-category');
        const name = editCategoryModal.find('input.category-name').val();

        $.ajax({
            url: updateCategoryUrl,
            type: 'POST',
            data: { id, name },
            success: ()=>{
                $(`[data-catagory-id="${id}"] .name`).text(name);
                editCategoryModal.modal('hide');
            },
            error: ()=>{
                editCategoryModal.modal('hide');
            }
        })
    });

    removeCategoryBtn.on('click', ()=>{
        const id = editCategoryModal.attr('data-category');

        $.ajax({
            url: removeCategoryUrl,
            type: 'POST',
            data: { id },
            success: ()=>{
                $(`[data-catagory-id="${id}"]`).remove();
                editCategoryModal.modal('hide');
            },
            error: ()=>{
                editCategoryModal.modal('hide');
            }
        })
    });
});