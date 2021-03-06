<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'ajax' => [
            'class' => 'frontend\modules\ajax\Ajax',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'clientS3' => [
            'class' => 'frontend\components\ClientS3',
            'key' => $params['s3']['key'],
            'secret' => $params['s3']['secret'],
            'region' => 'eu-central-1',
            'version' => 'latest'
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => $params['google']['id'],
                    'clientSecret' => $params['google']['secret'],
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => $params['facebook']['id'],
                    'clientSecret' => $params['facebook']['secret'],
                ],
                'vk' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => $params['vk']['id'],
                    'clientSecret' => $params['vk']['secret'],
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => $params['github']['id'],
                    'clientSecret' => $params['github']['secret'],
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'attributeParams' => [
                         'include_email' => 'true'
                     ],
                    'consumerKey' => $params['twitter']['id'],
                    'consumerSecret' => $params['twitter']['secret'],
                ]
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'wallets' => 'site/wallet',
                'categories' => 'site/category',
                'outcomes' => 'site/outcome',
                'incomes' => 'site/income',
                'account' => 'site/settings',
            ],
        ],

    ],
    'params' => $params,
];
