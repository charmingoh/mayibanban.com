<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'assetManager' => [
            'bundles'  => [
                'yii\bootstrap\BootstrapAsset'       => [
                    'sourcePath' => null,   // 一定不要发布该资源
                    'css'        => [
                        '//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css',
                    ]
                ],
                'yii\web\JqueryAsset'                => [
                    'sourcePath' => null,   // 一定不要发布该资源
                    'js'         => [
                        '//cdn.bootcss.com/jquery/2.1.4/jquery.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,   // 一定不要发布该资源
                    'js'         => [
                        '//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js',
                    ]
                ],
            ],
            'assetMap' => [
                'bootstrap.css' => '//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css',
                'jquery.js'     => '//cdn.bootcss.com/jquery/2.1.4/jquery.min.js',
                'bootstrap.js'  => '//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
            ],
        ],
    ],
    'params' => $params,
];
