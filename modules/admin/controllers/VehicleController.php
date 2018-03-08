<?php

namespace app\modules\admin\controllers;

use app\models\ar\VehicleTypeAR;
use app\models\VehicleModel;
use Yii;
use yii\helpers\Url;

class VehicleController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 车辆型号
     * @author: liuFangShuo
     */
    public function actionVehicleModel()
    {
        //查询有没有车辆类别
        //查询有没有质检流程

        return $this->render('model');
    }


    public function actionGetVehicleModel()
    {

    }

    public function actionAddVehicleModel()
    {
        return $this->render('add-model');

    }

    public function actionEditVehicleModel()
    {
        return $this->render('edit-model');

    }


    /**
     * 车辆类别
     * @author: liuFangShuo
     */
    public function actionVehicleType()
    {
        return $this->render('type');
    }

    public function actionGetVehicleType()
    {
        $model = new VehicleModel();
        $type = $model->getVehicleType();

        if(!empty($type)){
            foreach ($type as $k => $v){
                $type[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $type[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($type);
    }

    public function actionAddVehicleType()
    {
        $model = new VehicleTypeAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveType()) {
                    //成功跳转
                    return $this->redirect(Url::to(['vehicle/vehicle-type']));
                }
            }

            $model->getErrors();

        }

        return $this->render('add-type',['model' => $model]);
    }

    public function actionEditVehicleType()
    {
        $id = Yii::$app->request->get('id');
        $model = VehicleTypeAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['vehicle/vehicle-type']));
        }
        $model->setScenario('update');

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveType()) {
                    //成功跳转
                    return $this->redirect(Url::to(['vehicle/vehicle-type']));
                }
            }

            $model->getErrors();

        }

        return $this->render('edit-type',['model' => $model]);

    }


}
