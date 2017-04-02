'use strict';

jQuery(function ($) {
    console.log("settings page")

    /*
     * Crop avatar
     */

    const image = document.getElementById('crop');
    const preview = document.querySelectorAll('.preview');
    const avatarInput = $('#avatarInput');
    const cropArea = $('.crop-wrap');
    const mainAvatar = document.querySelector('#avatar');

    avatarInput.on('change', (event)=>{
        cropArea.fadeIn();
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend  = function(){
            cropper.replace(reader.result)
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            image.src = "";
        }
    });

    let cropper = new Cropper(image, {
        preview: '.preview',
        autoCropArea: 0.9,
    });

    $('#upload').on('click',()=>{
        cropper.getCroppedCanvas().toBlob(blob=>{
            const formData = new FormData;
            formData.append('file', blob);

            $.ajax({
                url: '/site/settings',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success:(res)=>{
                    cropArea.fadeOut();
                    mainAvatar.src = JSON.parse(res).url;
                },
                error: (err)=>{
                    cropArea.fadeOut();
                    console.log(err)
                }
            })
        })
    });


    /*
     * Declaration search input
     */

    const searchComponent = $('#global-search');
    const searchInput = $('#global-search [type="search"]');
    const searchResult = $('#search-results');

    let searchTimeout;
    searchInput.on('input', (event)=>{
        searchResult.html('');
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(()=>{
            if(event.target.value) {
                getDeclarationList(event.target.value)
            }
        },1000)
    });

    function getDeclarationList(text) {
        console.log(text)
        searchComponent.addClass('loading');
        $.ajax({
            url: '/ajax/settings/declarations',
            type: 'POST',
            data: {text},
            success: (response)=>{
                const result = JSON.parse(response);
                renderResultList(result, searchResult);
                searchComponent.removeClass('loading');
            },
            error: (err)=>{
                searchComponent.removeClass('loading');
            }
        })
    }

    function renderResultList(data, container) {
        if(!data.length) return container.html(`<li>No results</li>`)
        let html = data.map(item=>{
            if(!item.linkPDF) return '';
            return (
                `<li>
                    <p>${item.lastname} ${item.firstname}</p>
                    <p>${item.placeOfWork}</p>
                    <a href="${item.linkPDF}" download="declaration.pdf">Download Pdf</a>
                 </li>`
            )
        });

        container.html(html);
    }
});
