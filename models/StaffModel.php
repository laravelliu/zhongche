<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/27
 * Time: 17:14
 */

namespace app\models;


use app\models\ar\DepartmentAR;
use app\models\ar\RoleAR;
use app\models\ar\UserGroupAR;
use yii\base\Model;

class StaffModel extends Model
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

    /**
     * 获取员工组
     * @author: liuFangShuo
     */
    public function getStaffGroup()
    {
        $staffGroup = UserGroupAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $staffGroup;
    }

    /**
     * @author: liuFangShuo
     */
    public function getRole()
    {
        $role = RoleAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $role;
    }

}