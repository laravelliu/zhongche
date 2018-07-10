<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\UserAR;
use app\models\StaffModel;
use app\models\UserModel;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

/**
 * Default controller for the `admin` module
 */
class AdminController extends BaseController
{
    public function appendBehaviors()
    {
        return [
            'permission' => [
                'class' => PermissionFilter::className(),
                'failUrl' => '/login'
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 添加用户，只有超管可以添加
     * @return string|\yii\web\Response
     * @author: liuFangShuo
     */
    public function actionAddUser()
    {
        if(!Yii::$app->user->identity->isSuperAdmin()){
            return $this->render(Url::to(['admin/index']));
        }

        $model = new UserAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                $model->password_hash = md5($model->password_hash);
                $model->admin_photo = '/images/admin/user4-128x128.jpg';
                if ($model->save()) {

                    //成功跳转
                    return $this->redirect(Url::to(['admin/index']));
                }
            }

            $model->getErrors();

        }

        return $this->render('add-user',['model'=>$model]);
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

            //获取车间
            $workModel = new WorkshopModel();
            $workshop = $workModel->getWorkshopList();
            $workshopArr = ArrayHelper::map($workshop, 'id', 'name');

            //获取员工组
            $group = $deModel->getStaffGroup();
            $groupMap = ArrayHelper::map($group, 'id', 'name');

            //获取角色信息
            $roles = $deModel->getRole();
            $rolesArr = ArrayHelper::map($roles, 'id', 'name');

            foreach ($userList as $k => $v) {
                $haveRoles = [];

                //获取此人拥有的角色
                $haveRolesArr = $deModel->getRoleByUserId($v['id']);
                if (empty($haveRolesArr)) {
                    $userList[$k]['roles'] = '未分配角色';
                } else {
                    foreach ($haveRolesArr as $m => $n){
                        $haveRoles[] = $rolesArr[$n['role_id']];
                    }

                    $userList[$k]['roles'] = implode(',',$haveRoles);
                }


                $userList[$k]['group'] = ($v['is_admin'] == 0 && isset($groupMap[$v['group_id']])) ? $groupMap[$v['group_id']] : '-';
                $userList[$k]['department'] = isset($departmentMap[$v['department_id']])?$departmentMap[$v['department_id']] : '无';
                $userList[$k]['workshop'] = isset($workshopArr[$v['workshop_id']]) ? $workshopArr[$v['workshop_id']] : '无';
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

            if ($res) {
                return $this->ajaxReturn([],0);
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

        //是否是员工或者员工长
        $isStaff = $staffModel->isStaffOrStaffLeader($id);
        $groupList = [];

        //员工获取员工组
        if ($isStaff) {
            $model->setScenario('updateWithGroup');
            //获取员工组
            $group = $staffModel->getStaffGroup();
            $groupList = [0=>'无'] + ArrayHelper::map($group, 'id', 'name');
        }



        //是否需要车间信息
        $isNeedWorkshop = $staffModel->isNeedWorkshop($id);
        $workshopArr = [];
        if($isNeedWorkshop){
            //车间信息
            $model->setScenario('updateWithWorkshop');
            $workModel = new WorkshopModel();
            $workshop = $workModel->getWorkshopList();
            $workshopArr = ArrayHelper::map($workshop,'id','name');
        }


        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveUser()) {
                    //成功跳转
                    return $this->redirect(Url::to(['admin/index']));
                }
            }

            $model->getErrors();
        }

        //部门信息
        $department = $staffModel->getDepartmentList();
        $departmentList = ArrayHelper::map($department, 'id', 'name');

        return $this->render('edit-user', ['group' => $groupList, 'department' => $departmentList, 'workshop' => $workshopArr, 'model' => $model, 'isStaff' => $isStaff, 'isNeedWorkshop' => $isNeedWorkshop]);
    }


}
