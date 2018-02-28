<?php

namespace app\modules\admin\controllers;

use app\models\StaffModel;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;

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
     * 获取员工列表
     * @return object
     * @author: liuFangShuo
     */
    public function actionStaffList()
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


        return $this->ajaxReturn(['data' => $staffGroupList]);
    }

    /**
     * 添加
     * @author: liuFangShuo添加工位组
     */
    public function actionAddGroup()
    {
        $model = new StaffModel();
        $staffGroupList = $model->getStaffGroup();

        return $this->render('ad-staff-group');
    }

    /**
     * @author: liuFangShuo编辑工位组
     */
    public function actionEditStaffGroup()
    {
        $id = \Yii::$app->request->get('id',null);
    }
}

