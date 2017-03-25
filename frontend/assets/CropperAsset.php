<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class CropperAsset extends AssetBundle
{
    public $sourcePath = '@bower/cropperjs';

    public $css = [
        'dist/cropper.css',
    ];

    public $js = [
        'dist/cropper.js'
    ];

}