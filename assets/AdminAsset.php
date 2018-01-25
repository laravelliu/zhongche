<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 14:53
 */

namespace app\assets;


use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@adminlte';

    public $css = [
        'dist/css/skins/_all-skins.min.css'
    ];

    public $js = [
        YII_ENV_DEV ? 'bower_components/fastclick/lib/fastclick.js' : 'bower_components/fastclick/lib/fastclick.min.js',
        YII_ENV_DEV ? 'dist/js/adminlte.js' : 'dist/js/adminlte.min.js',
        YII_ENV_DEV ? 'dist/js/demo.js' : 'dist/js/demo.js',
    ];

    public $depends = [
        'app\assets\BaseAsset',
    ];
}