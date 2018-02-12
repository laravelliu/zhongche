<?php
/**
 * 登录等外部页面使用
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 14:53
 */

namespace app\assets;


use yii\web\AssetBundle;

class PublicAsset extends AssetBundle
{
    public $sourcePath = '@adminlte';

    public $css = [
        'plugins/iCheck/square/blue.css'
    ];

    public $js = [
        YII_ENV_DEV ? 'plugins/iCheck/icheck.js' : 'plugins/iCheck/icheck.min.js',
    ];

    public $depends = [
        'app\assets\BaseAsset',
    ];
}