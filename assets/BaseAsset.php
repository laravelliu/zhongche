<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 09:41
 */

namespace app\assets;

use yii\web\AssetBundle;

class BaseAsset extends AssetBundle
{
    public $sourcePath = '@adminlte';

    public $css = [
        YII_ENV_DEV ? 'bower_components/font-awesome/css/font-awesome.css' : 'bower_components/font-awesome/css/font-awesome.min.css',
        YII_ENV_DEV ? 'bower_components/Ionicons/css/ionicons.css' : 'bower_components/Ionicons/css/ionicons.min.css',
        YII_ENV_DEV ? 'dist/css/AdminLTE.css' :'dist/css/AdminLTE.min.css',
    ];
    public $js = [
        [YII_ENV_DEV ? '/js/html5shiv.js' : '/js/html5shiv.min.js', 'position' => \yii\web\View::POS_HEAD, 'condition' => 'lte IE9'],
        [YII_ENV_DEV ? '/js/respond.js' : '/js/respond.min.js', 'position' => \yii\web\View::POS_HEAD, 'condition' => 'lte IE9']
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}