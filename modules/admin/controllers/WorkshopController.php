<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\StationAR;
use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use app\models\StaffModel;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

class WorkshopController extends BaseController
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
     * 车间管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionWorkshop()
    {
        return $this->render('workshop');
    }

    /**
     * 获取车间列表
     * @author: liuFangShuo
     */
    public function actionGetWorkshop()
    {
        $model = new WorkshopModel();
        $workshopListInfo = $model->getWorkshopList();
        $workshops = ArrayHelper::map($workshopListInfo, 'id', 'name');
        $workshopList = [];

        foreach ($workshopListInfo as $workshop){
            $workshop['create_time'] = date('Y-m-d H:i:s', $workshop['create_time']);
            $workshop['update_time'] = date('Y-m-d H:i:s', $workshop['update_time']);
            $workshop['pWorkshop'] = isset($workshops[$workshop['pid']])?$workshops[$workshop['pid']]:'无';
            $workshop['sWorkshop'] = isset($workshops[$workshop['sid']])?$workshops[$workshop['sid']]:'无';
            $workshopList[] = $workshop;
        }
//print_r($workshopList)
        return $this->ajaxReturn($workshopList);
    }

    /**
     * 添加车间
     * @author: liuFangShuo
     */
    public function actionAddWorkshop()
    {
        $model = new WorkshopAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveWorkshop()) {
                    //成功跳转
                    return $this->redirect(Url::to(['workshop/workshop']));
                }
            }

            $model->getErrors();

        }

        //获取车间列表
        $wsModel = new WorkshopModel();
        $workshop = $wsModel->getWorkshop();
        $workshop = [ 0 => '无'] + $workshop;

        return $this->render('add-workshop',['model' => $model,'workshop' => $workshop]);
    }

    /**
     * 编辑车间
     * @author: liuFangShuo
     */
    public function actionEditWorkshop()
    {
        $id = Yii::$app->request->get('wsId', null);
        $model = WorkshopAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['workshop/workshop']));
        }

        $model->setScenario('update');

        //编辑成功
        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if($model->saveWorkshop()){
                    return $this->redirect(Url::to(['workshop/workshop']));
                }
            }

            $model->getErrors();

        }

        //获取车间列表
        $wsModel = new WorkshopModel();
        $workshop = $wsModel->getWorkshop();

        //除去本身添加没有的车间
        $workshop = [ 0 => '无'] + $workshop;
        unset($workshop[$id]);


        return $this->render('edit-workshop',['model' => $model,'workshop' => $workshop]);

    }

    /**
     * 工区管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionWorkArea()
    {
        return $this->render('area');
    }


    /**
     * 获取工区列表
     * @author: liuFangShuo
     */
    public function actionGetWorkArea()
    {
        $model = new WorkshopModel();

        //获取工区列表
        $workAreaListInfo = $model->getWorkAreaList();
        $workAreaList = [];

        //获取车间信息
        $workshop = $model->getWorkshop();

        foreach ($workAreaListInfo as $workArea){
            $workArea['create_time'] = date('Y-m-d H:i:s', $workArea['create_time']);
            $workArea['update_time'] = date('Y-m-d H:i:s', $workArea['update_time']);
            $workArea['workshop'] = $workshop[$workArea['workshop_id']];

            $workAreaList[] = $workArea;
        }

        return $this->ajaxReturn($workAreaList);
    }

    /**
     * 添加工区
     * @author: liuFangShuo
     */
    public function actionAddWorkArea()
    {

        //获取车间信息
        $wsModel = new WorkshopModel();
        $workshopList = $wsModel->getWorkshopList();

        if (empty($workshopList)) {
            $data = [
                'title' => '缺少车间信息',
                'content' => '没有车间信息，工区需要车间信息作为前置条件，请添加车间信息。',
                'button' => '添加车间',
                'url' => Url::to(['workshop/add-workshop'])
            ];
            return $this->render('empty', ['data' => $data]);
        }

        $model = new WorkAreaAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveWorkArea()) {
                    //成功跳转
                    return $this->redirect(Url::to(['workshop/work-area']));
                }
            }

            $model->getErrors();

        }

        $wsId = Yii::$app->request->get('wsId', null);
        if (!empty($wsId)) {
            $model->workshop_id = $wsId;
        }


        $workshop = $wsModel->getWorkshop();

        return $this->render('add-area', ['model' => $model,'workshop' => $workshop]);
    }

    /**
     * 编辑工区
     * @author: liuFangShuo
     */
    public function actionEditWorkArea()
    {
        $id = Yii::$app->request->get('waId', null);
        $model = WorkAreaAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['workshop/work-area']));
        }


        $model->setScenario('update');

        //编辑成功
        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if($model->saveWorkArea()){
                    return $this->redirect(Url::to(['workshop/work-area']));
                }
            }

            $model->getErrors();
        }

        //获取车间列表
        $wsModel = new WorkshopModel();
        $workshop = $wsModel->getWorkshop();

        return $this->render('edit-work-area',['model' => $model,'workshop' => $workshop]);
    }

    /**
     * 工位管理
     * @author: liuFangShuo
     */
    public function actionStation()
    {
        return $this->render('station');
    }

    /**
     * 获取工位信息
     * @author: liuFangShuo
     */
    public function actionGetStation()
    {
        $model = new WorkshopModel();
        //获取工位列表
        $stationListInfo = $model->getStationList();
        $stations = [0=>'无'] + ArrayHelper::map($stationListInfo,'id','name');

        //获取车间信息
        $workshop = $model->getWorkshop();

        //获取工区信息
        $workAreaList = $model->getWorkAreaList();
        $workArea = ArrayHelper::map($workAreaList,'id','name');
        $stationList = [];

        if (!empty($stationListInfo)) {
            foreach ($stationListInfo as $station){
                $station['create_time'] = date('Y-m-d H:i:s', $station['create_time']);
                $station['update_time'] = date('Y-m-d H:i:s', $station['update_time']);
                $station['workshop'] = $workshop[$station['workshop_id']];
                $station['workArea'] = $workArea[$station['work_area_id']];
                $station['pStation'] = $stations[$station['pid']];
                $station['sStation'] = $stations[$station['sid']];

                $stationList[] = $station;
            }
        }

        return $this->ajaxReturn($stationList);
    }

    /**
     * 添加工位
     * @author: liuFangShuo
     */
    public function actionAddStation()
    {
        //获取工区
        $wsModel = new WorkshopModel();
        $workAreaList = $wsModel->getWorkAreaList();

        if (empty($workAreaList)) {
            $data = [
                'title' => '缺少工区信息',
                'content' => '没有工区信息，工位需要工区信息作为前置条件，请添加工区信息。',
                'button' => '添加工区',
                'url' => Url::to(['workshop/add-work-area'])
            ];
            return $this->render('empty', ['data' => $data]);
        }

        $model = new StationAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveStation()) {
                    //成功跳转
                    return $this->redirect(Url::to(['workshop/station']));
                }
            }

            $model->getErrors();

        }


        $workAreaShop = ArrayHelper::map($workAreaList,'id','workshop_id');
        $workAreaId = Yii::$app->request->get('waId',0);

        if (!empty($workAreaId)) {
            $model->workshop_id = $workAreaShop[$workAreaId];
            $model->work_area_id = $workAreaId;
        }

        //获取工位列表

        return $this->render('add-station', ['model' => $model, 'wsModel' => $wsModel]);

    }

    /**
     * 编辑工位
     * @author: liuFangShuo
     */
    public function actionEditStation()
    {
        $id = Yii::$app->request->get('id', null);
        $model = StationAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['workshop/station']));
        }

        $model->setScenario('update');

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveStation()) {
                    //成功跳转
                    return $this->redirect(Url::to(['workshop/station']));
                }
            }

            $model->getErrors();
        }

        $wsModel = new WorkshopModel();
        $stationList = $wsModel->getStation($model->work_area_id);
        unset($stationList[$id]);

        return $this->render('edit-station', ['model' => $model, 'wsModel' => $wsModel, 'stationList' => $stationList]);
    }


    /**
     * 根据车间获取工区列表(用于三级联动)
     * @author: liuFangShuo
     */
    public function actionGetWorkAreaList()
    {
        $wsId = Yii::$app->request->get('wsid', null);
        if (empty($wsId)) {
            return $this->ajaxReturn([],1,'没有车间id');
        }

        $model = new WorkshopModel();
        $workarea = $model->getWorkArea($wsId);

        return $this->ajaxReturn(['workArea' =>$workarea ]);
    }

    /**
     * 根据工区获取工位列表(用于三级联动)
     * @author: liuFangShuo
     */
    public function actionGetStationList()
    {
        $waId = Yii::$app->request->get('waid', null);
        if (empty($waId)) {
            return $this->ajaxReturn([],1,'没有工区id');
        }

        $model = new WorkshopModel();
        $station = $model->getStation($waId);

        return $this->ajaxReturn(['station' => $station ]);
    }

    /**
     * 工位分配人员组
     * @author: liuFangShuo
     */
    public function actionUserGroupStation()
    {
        $id = Yii::$app->request->get('id',null);
        $model = new WorkshopModel();
        $station = $model->getStationById($id);

        if(empty($station)){
            return $this->redirect(Url::to(['workshop/station']));
        }

        //获取车间
        $workshop = $model->getWorkshopById($station['workshop_id']);

        //获取工区
        $workArea = $model->getWorkAreaById($station['work_area_id']);

        $name = $workshop->name.'-'.$workArea->name.'-'.$station['name'];

        //
        $staffModel = new StaffModel();
        $group = $staffModel->getStaffGroup();
        $all = ArrayHelper::map($group,'id','name');

        $selectedGroup = $model->getUserGroupByStation($id);
        $selected = [];
        $unSelect = [];

        if(empty($selectedGroup)){
            $unSelect = $all;
        } else {
            //已经选择的类型
            foreach ($selectedGroup as $k => $v) {
                $selected[$v['user_group_id']] = $all[$v['user_group_id']];
            }

            $unSelect = array_diff($all,$selected);

        }

        return $this->render('distribution-user-group',['name' => $name,'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect, 'id'=>$id, 'url'=>Url::to(['workshop/group-station-distribution'])]]);
    }

    /**
     *  工位分配员工组
     * @author: liuFangShuo
     */
    public function actionGroupStationDistribution()
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

            $model = new WorkshopModel();
            $res = $model->saveStationGroup($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1, $model->getFirstError('name'));
        }

        return false;
    }
}