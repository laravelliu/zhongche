<?php

/**
 * 数据库配置
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/15
 * Time: 09:02
 */

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=zhongche',
    'username' => 'root',
    'password' => '1',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

if(YII_ENV_DEV) {
    $db = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=211.159.176.151;dbname=zhongche_test',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',

        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
    ];
}

return $db;
