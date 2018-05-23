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

    /**
     * 根据key形成新数组
     * @param $arr
     * @param $key
     * @return array
     * @author: liuFangShuo
     */
    public static function arrayChangeKey($arr,$key)
    {
        $changeArr = array();
        if(is_array($arr) && !empty($arr)){
            foreach ($arr as $item)
            {
                $changeArr[$item[$key]] = $item;
            }
        }
        return $changeArr;
    }

    /**
     * 根据pid进行排序
     * @param $arr
     * @return array
     * @author: liuFangShuo
     */
    public static function arraySortBySid($arr)
    {
        $return = [];
        $count = count($arr);

        //找到头
        foreach ($arr as $k => $v) {
            if($v['pid'] == 0){
                $return[] = $v;
                $sid = $v['sid'];
                break;
            }
        }

        for($i=1; $i<$count; $i++){
            $return[] = $arr[$sid];
            $sid = $arr[$sid]['sid'];
        }

        return $return;
    }
}