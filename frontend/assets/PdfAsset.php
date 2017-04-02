<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class PdfAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'jspdf/dist/jspdf.min.js',
        'jspdf-autotable/dist/jspdf.plugin.autotable.js',
    ];
}