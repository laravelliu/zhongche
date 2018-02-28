<?php

namespace app\modules\admin\controllers;

use app\models\ar\UserGroupAR;
use app\models\StaffModel;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class StaffController extends BaseController
{
    /**
     * 员工信息
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
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

            //获取工位
            $stationModel = new WorkshopModel();
            $stationList = $stationModel->getStationList();
            $stations = ArrayHelper::map($stationList,'id', 'name');

            foreach ($staffGroupList as $k => $v){
                $staffGroupList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $staffGroupList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $staffGroupList[$k]['station'] = $stations[$v['station_id']];
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
        $workshopModel = new WorkshopModel();
        $stationList = $workshopModel->getStationList();

        if(empty($stationList)){
            $data = [
                'title' => '缺少工位信息',
                'content' => '没有工位信息，员工组需要工位信息作为前置条件，请添加员工组信息。',
                'button' => '添加工位',
                'url' => Url::to(['workshop/add-station'])
            ];

            return $this->render('workshop/empty',['data' => $data]);
        }

        $model = new UserGroupAR();
        $model->setScenario('create');
        $station = ArrayHelper::map($stationList,'id','name');

        $sId = Yii::$app->request->get('id', null);
        if(!empty($sId) && isset($station[$sId])){
            $model->station_id = $sId;
        }

        if (Yii::$app->request->isPost) {
            if(Yii::$app->request->isPost){

                if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                    if ($model->saveStaffGroup()) {
                        //成功跳转
                        return $this->redirect(Url::to(['staff/staff-group']));
                    }
                }

                $model->getErrors();

            }
        }

        return $this->render('add-staff-group',['model' => $model, 'station' => $station]);
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

