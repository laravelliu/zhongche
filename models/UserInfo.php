<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/27
 * Time: 17:14
 */

namespace app\models;


use app\models\ar\DepartmentAR;
use yii\base\Model;

class UserInfo extends Model
{
    /**
     * 获取部门信息
     * @author: liuFangShuo
     */
    public function getDepartmentList()
    {
        $department = DepartmentAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $department;
    }

}