<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\StaffModel;
use app\models\UserModel;
use yii\helpers\ArrayHelper;


/**
 * Default controller for the `admin` module
 */
class AdminController extends BaseController
{
    /*public function appendBehaviors()
    {
        return [
            'permission' => [
                'class' => PermissionFilter::className(),
            ]
        ];
    }*/

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取用户列表
     * @author: liuFangShuo
     */
    public function actionGetUsers()
    {
        $model = new UserModel();
        $userList = $model->getUserList();

        if(!empty($userList)){

            //获取部门信息
            $deModel = new StaffModel();
            $department = $deModel->getDepartmentList();
            $departmentMap = ArrayHelper::map($department, 'id', 'name');

            //获取员工组
            $group = $deModel->getStaffGroup();
            $groupMap = ArrayHelper::map($group, 'id', 'name');

            foreach ($userList as $k => $v){
                $userList[$k]['group'] = ($v['is_admin'] == 0 && isset($groupMap[$v['group_id']])) ? $groupMap[$v['group_id']] : '-';
                $userList[$k]['department'] = isset($departmentMap[$v['department_id']])?$departmentMap[$v['department_id']] : '无';
                $userList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $userList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($userList);
    }

    public function actionUserList()
    {
        return $this->render('test');
    }

}
