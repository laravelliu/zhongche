<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\UserGroupAR;
use app\models\StaffModel;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class StaffController extends BaseController
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
     * 员工组管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionStaffGroup()
    {
        return $this->render('group');
    }

    /**
     * 获取员工组列表
     * @return object
     * @author: liuFangShuo
     */
    public function actionGroupList()
    {
        $model = new StaffModel();
        $staffGroupList = $model->getStaffGroup();

        if(!empty($staffGroupList)){

            foreach ($staffGroupList as $k => $v){
                $staffGroupList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $staffGroupList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($staffGroupList);
    }

    /**
     * 添加工位组
     * @author: liuFangShuo
     */
    public function actionAddGroup()
    {

        $model = new UserGroupAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveStaffGroup()) {
                    //成功跳转
                    return $this->redirect(Url::to(['staff/staff-group']));
                }
            }

            $model->getErrors();

        }

        return $this->render('add-staff-group',['model' => $model]);
    }

    /**
     * 编辑工位组
     * @author: liuFangShuo
     */
    public function actionEditStaffGroup()
    {
        $id = Yii::$app->request->get('id',null);
        $model = UserGroupAR::findOne(['is_deleted' => STATUS_FALSE, 'id' => $id]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['staff/staff-group']));
        }
        $model->setScenario('update');

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveStaffGroup()) {
                    //成功跳转
                    return $this->redirect(Url::to(['staff/staff-group']));
                }
            }

            $model->getErrors();
        }

        return $this->render('edit-staff-group',['model' => $model]);
    }
}

