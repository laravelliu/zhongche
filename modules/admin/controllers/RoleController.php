<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\RoleAR;
use app\models\StaffModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class RoleController extends BaseController
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
     * 角色管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取角色列表
     * @return object
     * @author: liuFangShuo
     */
    public function actionGetRole()
    {
        $model = new StaffModel();
        $roleList = $model->getRole();

        if(!empty($roleList)){
            foreach ($roleList as $k => $v){
                $roleList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $roleList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($roleList);
    }

    /**
     * 添加角色
     * @return string
     * @author: liuFangShuo
     */
    public function actionAddRole()
    {
        $model = new RoleAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model ->validate()){
                if($model->saveRole()){
                    return $this->redirect(Url::to(['role/index']));
                }
            }

            $model->getErrors();
        }

        return $this->render('add-role',['model' => $model]);
    }

    /**
     * 编辑角色
     * @return string
     * @author: liuFangShuo
     */
    public function actionEditRole()
    {
        $id = Yii::$app->request->get('id', null);
        $model = RoleAR::findOne(['is_deleted' => STATUS_FALSE, 'is_sys' => STATUS_FALSE, 'id' => $id]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['role/index']));
        }

        $model->setScenario('update');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model ->validate()){
                if($model->saveRole()){
                    return $this->redirect(Url::to(['role/index']));
                }
            }

            $model->getErrors();
        }

        return $this->render('edit-role', ['model' => $model]);
    }

    /**
     * 角色分配
     * @author: liuFangShuo
     */
    public function actionDistribution()
    {
        $id = Yii::$app->request->get('id', null);
        $role = RoleAR::findOne(['is_deleted' => STATUS_FALSE, 'id' => $id]);

        if(empty($id) || empty($role)){
            return $this->redirect(Url::to(['role/index']));
        }

        $model = new StaffModel();
        $permissionList = $model->getPermission();
        $all = ArrayHelper::map($permissionList,'id','display_name');

        $selectedPermission = $model->getPermissionByRoleId($id);
        $selected = [];
        $unSelect = [];

        if(empty($selectedPermission)){
            $unSelect = $all;
        } else {
            //已经选择的类型
            foreach ($selectedPermission as $k => $v) {
                $selected[$v['permission_id']] = $all[$v['permission_id']];
            }

            $unSelect = array_diff($all,$selected);

        }

        return $this->render('distribution',['role' => $role, 'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect, 'id'=>$id, 'url'=>Url::to(['role/save-role'])]]);
    }

    /**
     * 保存权限
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
                    $selectId[] =  $v['0'];
                }
            }

            if (!empty($unSelect)) {
                foreach ($unSelect as $k => $v) {
                    $unSelectId[] =  $v['0'];
                }
            }

            $model = new StaffModel();
            $res = $model->saveRolePermission($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1, $model->getFirstError('name'));
        }

        return false;
    }
}
