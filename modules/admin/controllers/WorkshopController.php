<?php

namespace app\modules\admin\controllers;

use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use app\models\WorkshopModel;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

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
        $workshopList = [];

        foreach ($workshopListInfo as $workshop){
            $workshop['create_time'] = date('Y-m-d H:i:s', $workshop['create_time']);
            $workshop['update_time'] = date('Y-m-d H:i:s', $workshop['update_time']);
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
        $workshopList = $wsModel->getWorkshopList();
        $workshop = ArrayHelper::map($workshopList,'id','name');
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
        if(empty($id)){
           return $this->redirect(Url::to(['workshop/workshop']));
        }

        $model = WorkshopAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
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
        $workshopList = $wsModel->getWorkshopList();
        $workshop = ArrayHelper::map($workshopList,'id','name');

        //除去本身添加没有的车间
        $workshop = [ 0 => '无'] + $workshop;
        unset($workshop[$id]);


        return $this->render('edit-workshop',['model' => $model,'workshop' => $workshop]);

    }

    /**
     * 产线管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionWorkArea()
    {
        //获取车间信息
        $wsModel = new WorkshopModel();
        $workshopList = $wsModel->getWorkshopList();

        if (empty($workshopList)) {
            $data = [
                'title' => '缺少车间信息',
                'content' => '没有车间信息，产线需要车间信息作为前置条件，请添加车间信息。',
                'button' => '添加车间',
                'url' => Url::to(['workshop/add-workshop'])
                ];
            return $this->render('empty', ['data' => $data]);
        }


        return $this->render('area');
    }


    /**
     * 获取产线列表
     * @author: liuFangShuo
     */
    public function actionGetWorkArea()
    {
        $model = new WorkshopModel();

        //获取产线列表
        $workAreaListInfo = $model->getWorkAreaList();
        $workAreaList = [];

        //获取车间信息
        $workshopList = $model->getWorkshopList();
        $workshop = ArrayHelper::map($workshopList,'id','name');

        foreach ($workAreaListInfo as $workArea){
            $workArea['create_time'] = date('Y-m-d H:i:s', $workArea['create_time']);
            $workArea['update_time'] = date('Y-m-d H:i:s', $workArea['update_time']);
            $workArea['workshop'] = $workshop[$workArea['workshop_id']];

            $workAreaList[] = $workArea;
        }

        return $this->ajaxReturn($workAreaList);
    }

    /**
     * 添加产线
     * @author: liuFangShuo
     */
    public function actionAddWorkArea()
    {
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

        //获取车间列表
        $wsModel = new WorkshopModel();
        $workshopList = $wsModel->getWorkshopList();
        $workshop = ArrayHelper::map($workshopList,'id','name');

        return $this->render('add-area', ['model' => $model,'workshop' => $workshop]);
    }

    /**
     * 编辑产线
     * @author: liuFangShuo
     */
    public function actionEditWorkArea()
    {
        $id = Yii::$app->request->get('waId', null);
        if(empty($id)){
            return $this->redirect(Url::to(['workshop/work-area']));
        }

        $model = WorkAreaAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
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
        $workshopList = $wsModel->getWorkshopList();
        $workshop = ArrayHelper::map($workshopList,'id','name');


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
}