<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class SiteWalletAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/main.js',
        'js/site/wallet.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}