<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class SiteIncomesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/site/incomes.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}