'use strict';

jQuery(function ($) {
    //crop preview

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

    console.log("settings page")
});
