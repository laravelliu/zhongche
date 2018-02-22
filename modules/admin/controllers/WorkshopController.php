<?php

namespace app\modules\admin\controllers;

use app\models\ar\WorkshopAR;
use app\models\WorkshopForm;
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
        return $this->render('area');
    }


    /**
     * 获取车间列表
     * @author: liuFangShuo
     */
    public function actionGetWorkArea()
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
     * 工位管理
     * @author: liuFangShuo
     */
    public function actionStation()
    {
        return $this->render('station');
    }
}