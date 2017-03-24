'use strict';

jQuery(function ($) {
    //crop preview

    const image = document.getElementById('crop');
    const preview = document.querySelectorAll('.preview');
    const avatarInput = $('#avatarInput');
    const cropArea = $('.crop-wrap');

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
        background: false,
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
                    console.log("success", res)
                },
                error: (err)=>{
                    cropArea.fadeOut();
                    console.log("err", err)
                }
            })
        })
    });

    console.log("settings page")
});
