<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-frontend',
    'name'                => '蚂蚁搬搬',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'language'            => 'zh-CN',
    'timeZone'            => 'Asia/Shanghai',
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute'        => 'question/index',
    'version'             => 'v0.4.5',
    'components'          => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                // ID 相关的信息应该在 main-local 中填写 
                'QQ' => [
                    'class' => 'common\components\oauth\QQ',
                    'clientId' => 'app_id',
                    'clientSecret' => 'app_secret',
                ],
                'Weibo' => [
                    'class' => 'common\components\oauth\Weibo',
                    'clientId' => 'weibo_key',
                    'clientSecret' => 'weibo_secret',
                ],
            ],
        ],
        'formatter'    => [
            'dateFormat'     => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
        ],
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
                    ],
                    'jsOptions'  => [
                        'position' => \yii\web\View::POS_BEGIN,
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,   // 一定不要发布该资源
                    'js'         => [
                        '//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js',
                    ],
                    'jsOptions'  => [
                        'position' => \yii\web\View::POS_BEGIN,
                    ]
                ],
            ],
            'assetMap' => [
                'bootstrap.css' => '//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css',
                'jquery.js'     => '//cdn.bootcss.com/jquery/2.1.4/jquery.min.js',
                'bootstrap.js'  => '//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js',
            ],
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'loginUrl'        => ['user/login'],
            'enableAutoLogin' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                'question/<sort:(hottest|latest|unanswered)>'   => 'question/index',
                'question/<id:\d+>'                             => 'question/view',
                'answer/<id:\d+>/url'                           => 'answer/url',
                'p/<alias>'                                     => 'profile/view',
                'p/<alias>/<action:(asks|answers|collections)>' => 'profile/<action>',
            ],
        ],
    ],
    'params'              => $params,
];
