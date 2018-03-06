<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/4
 * Time: 11:51
 */

namespace app\models;


use app\models\ar\ParamAR;
use yii\base\Model;

class ParamModel extends Model
{
    /**
     * 获取参数
     * @author: liuFangShuo
     */
    public function getParamList($type = 0)
    {
        $params = ParamAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $params;
    }
}