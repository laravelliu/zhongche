<?php

namespace app\modules\admin\controllers;

use app\models\ar\PermissionAR;
use app\models\ar\ProcessAR;
use app\models\StaffModel;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

class PermissionController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取权限
     * @return object
     * @author: liuFangShuo
     */
    public function actionGetPermission()
    {
        $model = new StaffModel();
        $permissionList = $model->getPermission();

        $permission = [0=>'无'] + ArrayHelper::map($permissionList,'id','display_name');

        if (!empty($permissionList)) {
            foreach ($permissionList as $k => $v){
                $permissionList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $permissionList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $permissionList[$k]['parent_id'] = $permission[$v['parent_id']];
            }
        }

        return $this->ajaxReturn($permissionList);
    }

    /**
     * 添加权限
     * @return string|\yii\web\Response
     * @author: liuFangShuo
     */
    public function actionAddPermission()
    {
        $model = new PermissionAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->savePermission()) {
                    //成功跳转
                    return $this->redirect(Url::to(['permission/index']));
                }
            }

            $model->getErrors();

        }

        $staffModel = new StaffModel();
        $permission = $staffModel->getPermission();
        $permissionList = [0=>'无'] + ArrayHelper::map($permission,'id','display_name');

        return $this->render('add-permission',['model' => $model,'permissionList' => $permissionList]);
    }

    /**
     * 编辑权限
     * @return string
     * @author: liuFangShuo
     */
    public function actionEditPermission()
    {
        $id = Yii::$app->request->get('id', null);
        $model = PermissionAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['permission/index']));
        }

        $model->scenarios('update');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->savePermission()) {
                    //成功跳转
                    return $this->redirect(Url::to(['permission/index']));
                }
            }

            $model->getErrors();
        }

        $staffModel = new StaffModel();
        $permission = $staffModel->getPermission();
        $permissionList = [0=>'无'] + ArrayHelper::map($permission,'id','display_name');
        unset($permissionList[$id]);

        return $this->render('edit-permission',['model' => $model, 'permissionList' => $permissionList]);
    }

}
