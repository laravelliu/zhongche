<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => '操作日志管理系统',
    'version' => '1.0.0',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Chongqing', //时区
    'language' => 'zh-CN',//'zh-CN', //目标语言语言包
    //'sourceLanguage' => 'zh-CN',//源语言语言包(默认是英语)
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@adminlte' => '@vendor/almasaeed2010/adminlte',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XnStWhTTkO_yfiDFJHze-XoQI3ypNS6o',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\ar\UserAR',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                //错误日志
                'error' => [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error', 'warning'],
                    'message' => [
                        'from' => ['565325162@qq.com'],
                        'to' => $params['errorEmail'],
                        'subject' => '中车错误邮件'
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:400',
                        'yii\base\ErrorException:32'
                    ]
                ],
                //记录日志
                'info' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                    'logFile' => '@app/runtime/logs/info/'.date('Ymd',time()).'.log',
                    'logVars' => [],
                ]
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/rules.php'),
        ],

        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@bower/jquery/dist',
                    'js' => [YII_ENV_DEV ? 'jquery.js': 'jquery.min.js']
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@bower/bootstrap/dist',
                    'css' => [YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@bower/bootstrap/dist',
                    'js' => [YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js']
                ]
            ],
        ],


    ],
    'params' => $params,

    /**
     * 后台模块
     */
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ]
    ],


];

if (YII_ENV_DEV) {

    if (YII_DEBUG) {
        // configuration adjustments for 'dev' environment
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => 'yii\debug\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
        ];
    }

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
