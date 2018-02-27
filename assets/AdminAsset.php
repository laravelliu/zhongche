<?php
/**
 * 登录之后页面使用
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 14:53
 */

namespace app\assets;

use Yii;
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


    /**
     * 追加css
     * @author: liuFangShuo
     */
    private function addCss(array $addCss)
    {
        $this->css = array_merge( $this->css, $addCss);
    }

    /**
     * 追加js
     * @author: liuFangShuo
     */
    private function addJs(array $addJs)
    {
        $this->js = array_merge( $this->js, $addJs);
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $requestPath = Yii::$app->request->url;
        $css = [];
        $js = [];

        switch ($requestPath)
        {
            case '/admin/admin/user-list':
                $js = ['bower_components/jquery-slimscroll/jquery.slimscroll.min.js'];
                break;
            case '/admin/workshop/workshop':
            case '/admin/workshop/work-area':
            case '/admin/workshop/station':
            case '/admin/quality/index':
                $css = ['bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'];
                $js = [
                    'bower_components/datatables.net/js/jquery.dataTables.min.js',
                    'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
                    'bower_components/jquery-slimscroll/jquery.slimscroll.min.js'
                    ];
                break;
            default:
                break;

        }

        if (! empty($css)) {
            $this->addCss($css);
        }

        if (! empty($js)) {
            $this->addJs($js);
        }
    }

}