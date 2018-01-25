<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2017/4/13
 * Time: 10:08
 */
namespace app\common\helpers;
class WebHelper
{
    /**
     * 保存权利
     * @return string
     * @author: liuFangShuo
     */
    public static function power()
    {
        return 'Copyright &copy; 2014-2016 <a href="https://www.baidu.com" target="_blank">' . \Yii::$app->name . '</a>。';
    }
}