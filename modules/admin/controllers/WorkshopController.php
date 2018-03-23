<?php

namespace app\modules\admin\controllers;

use app\models\ar\StationAR;
use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

class WorkshopController extends BaseController
{
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
            $workshop['pWorkshop'] = $workshops[$workshop['id']];
            $workshopList[] = $workshop;
        }

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
}