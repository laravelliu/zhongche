<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\DepartmentAR;
use app\models\StaffModel;
use Yii;
use yii\helpers\Url;

class DepartmentController extends BaseController
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
     * 部门管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取部门列表
     * @author: liuFangShuo
     */
    public function actionDepartmentList()
    {
        $model = new StaffModel();
        $departmentList = $model->getDepartmentList();

        foreach ($departmentList as $k => $department){
            $departmentList[$k]['create_time'] = date('Y-m-d H:i:s', $department['create_time']);
            $departmentList[$k]['update_time'] = date('Y-m-d H:i:s', $department['update_time']);
        }

        return $this->ajaxReturn($departmentList);
    }

    /**
     * 添加部门
     * @author: liuFangShuo
     */
    public function actionAddDepartment()
    {
        $model = new DepartmentAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveDepartment()) {
                    //成功跳转
                    return $this->redirect(Url::to(['department/index']));
                }
            }

            $model->getErrors();

        }
        return $this->render('add-department',['model' => $model]);
    }

    /**
     * 编辑部门
     * @author: liuFangShuo
     */
    public function actionEditDepartment()
    {
        $id = Yii::$app->request->get('deId');
        $model = DepartmentAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['department/index']));
        }
        $model->setScenario('update');

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveDepartment()) {
                    //成功跳转
                    return $this->redirect(Url::to(['department/index']));
                }
            }

            $model->getErrors();
        }

        return $this->render('edit-department', ['model' => $model]);
    }

}
