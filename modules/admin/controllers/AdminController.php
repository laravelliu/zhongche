<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\StaffModel;
use app\models\UserModel;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

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

    /**
     * 分配角色
     * @author: liuFangShuo
     */
    public function actionDistributionRole()
    {
        $id = Yii::$app->request->get('id', null);
        $model = new StaffModel();

        $userInfo = $model->getUserById($id);

        if(empty($id) || empty($userInfo)){
            return $this->redirect(Url::to(['admin/index']));
        }

        $roleList = $model->getRole();
        $all = ArrayHelper::map($roleList,'id','name');

        $selectedRole = $model->getRoleByUserId($id);
        $selected = [];
        $unSelect = [];

        if(empty($selectedRole)){
            $unSelect = $all;
        } else {
            //已经选择的类型
            foreach ($selectedRole as $k => $v) {
                $selected[$v['role_id']] = $all[$v['role_id']];
            }

            $unSelect = array_diff($all,$selected);

        }

        return $this->render('distribution',['user' => $userInfo, 'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect, 'id'=>$id, 'url'=>Url::to(['admin/save-role'])]]);

    }

    /**
     * 保存人员角色
     * @author: liuFangShuo
     */
    public function actionSaveRole()
    {
        if (Yii::$app->request->isAjax) {
            $select = Yii::$app->request->post('selected',null);
            $unSelect = Yii::$app->request->post('unSelect',null);
            $id = Yii::$app->request->post('id', null);

            if (empty($id)) {
                return $this->ajaxReturn([],1,'不存在id');
            }

            $selectId = [];
            $unSelectId = [];

            if (!empty($select)) {
                foreach ($select as $k => $v) {
                    $selectId[] =  $v[0];
                }
            }

            if (!empty($unSelect)) {
                foreach ($unSelect as $k => $v) {
                    $unSelectId[] =  $v[0];
                }
            }

            $model = new StaffModel();
            $res = $model->saveUserRole($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1, $model->getFirstError('name'));

        }

        return false;
    }

    /**
     * 编辑用户
     * @author: liuFangShuo
     */
    public function actionEditUser()
    {
        $id = Yii::$app->request->get('id', null);
        $staffModel = new StaffModel();
        $model = $staffModel->getUserById($id);

        if (empty($id) || empty($model)) {
            return $this->redirect(Url::to(['admin/index']));
        }

        $model->setScenario('update');

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveUser()) {
                    //成功跳转
                    return $this->redirect(Url::to(['admin/index']));
                }
            }

            $model->getErrors();
        }

        //员工获取员工组
        if (!$model->is_admin) {
            //获取员工组
            $group = $staffModel->getStaffGroup();
            $groupList = ArrayHelper::map($group, 'id', 'name');
        } else {
            $groupList = [0=>'无'];
        }

        //部门信息
        $department = $staffModel->getDepartmentList();
        $departmentList = ArrayHelper::map($department, 'id', 'name');

        return $this->render('edit-user', ['group' => $groupList, 'department' => $departmentList, 'model' => $model]);
    }


    public function actionUserList()
    {
        return $this->render('test');
    }

}
