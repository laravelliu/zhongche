<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\VehicleModelAR;
use app\models\ar\VehicleTypeAR;
use app\models\QualityModel;
use app\models\VehicleModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class VehicleController extends BaseController
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
     * 车辆首页
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取车辆信息
     * @author: liuFangShuo
     */
    public function actionGetVehicleInfo()
    {
        $vehicleModel = new VehicleModel();
        $vehicleList = $vehicleModel->getVehicleList();

        //获取车辆型号
        $vehicleType = $vehicleModel->getVehicleType();
        $typeList = ArrayHelper::map($vehicleType, 'id','name');
        //获取车辆类型
        $vehicleModelList = $vehicleModel->getVehicleModel();
        $modelList = ArrayHelper::map($vehicleModelList, 'id','name');


        if(!empty($vehicleList)){
            foreach ($vehicleList as $k => $v) {
                $vehicleList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $vehicleList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $vehicleList[$k]['vehicle_type'] = isset($typeList[$v['vehicle_type_id']]) ? $typeList[$v['vehicle_type_id']] : '无';
                $vehicleList[$k]['vehicle_model'] = isset($modelList[$v['vehicle_model_id']]) ? $modelList[$v['vehicle_model_id']] : '无';
            }
        }

        return $this->ajaxReturn($vehicleList);
    }

    /**
     * 车辆型号
     * @author: liuFangShuo
     */
    public function actionVehicleModel()
    {
        //查询有没有车辆类别
        $vehicleModel = new VehicleModel();
        $vehicleType = $vehicleModel->getVehicleType();

        if(empty($vehicleType)){
            $data = [
                'title' => '缺少车辆类型信息',
                'content' => '没有车辆类型信息，车辆型号需要车辆类型作为前置条件，请添加车辆类型。',
                'button' => '添加车辆类型',
                'url' => Url::to(['vehicle/add-vehicle-type'])
            ];

            return $this->render('/workshop/empty', ['data' => $data]);
        }

        //查询有没有质检流程
        $qualityModel = new QualityModel();
        $qualityType = $qualityModel->getQualityType();

        if(empty($qualityType)){
            $data = [
                'title' => '缺少质检流程信息',
                'content' => '没有质检流程信息，车辆型号需要质检流程信息作为前置条件，请添加质检流程信息。',
                'button' => '添加车辆型号',
                'url' => Url::to(['quality/add-quality-type'])
            ];

            return $this->render('/workshop/empty', ['data' => $data]);
        }


        return $this->render('model');
    }


    /**
     * 获取车辆型号
     * @author: liuFangShuo
     */
    public function actionGetVehicleModel()
    {
        $model = new VehicleModel();
        $vehicleModelList = $model->getVehicleModel();

        if (!empty($vehicleModelList)) {
            //获取质检流程
            $qualityModel = new QualityModel();
            $qualityType = $qualityModel->getQualityType();
            $qualityTypeList = ArrayHelper::map($qualityType, 'id', 'name');

            //获取车辆类型
            $vehicleModel = new VehicleModel();
            $vehicleType = $vehicleModel->getVehicleType();
            $vehicleTypeList = ArrayHelper::map($vehicleType, 'id', 'name');

            foreach ($vehicleModelList as $k => $v) {

                $vehicleModelList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $vehicleModelList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $vehicleModelList[$k]['vehicle_type'] = $vehicleTypeList[$v['vehicle_type_id']];
                $vehicleModelList[$k]['type'] = $qualityTypeList[$v['type_id']];
            }
        }

        return $this->ajaxReturn($vehicleModelList);
    }

    /**
     * 添加车辆型号
     * @return string
     * @author: liuFangShuo
     */
    public function actionAddVehicleModel()
    {
        $model = new VehicleModelAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveModel()) {
                    //成功跳转
                    return $this->redirect(Url::to(['vehicle/vehicle-model']));
                }
            }

            $model->getErrors();
        }

        //获取质检流程
        $qualityModel = new QualityModel();
        $qualityType = $qualityModel->getQualityType();

        if (empty($qualityType)) {
            $data = [
                'title' => '缺少质检流程信息',
                'content' => '没有质检流程信息，车辆型号需要质检流程信息作为前置条件，请添加质检流程信息。',
                'button' => '添加车辆型号',
                'url' => Url::to(['quality/add-quality-type'])
            ];

            return $this->render('/workshop/empty', ['data' => $data]);
        } else {
            $qualityTypeList = ArrayHelper::map($qualityType,'id','name');
        }

        //获取车辆类型
        $vehicleModel = new VehicleModel();
        $vehicleType = $vehicleModel->getVehicleType();

        if(empty($vehicleType)){
            $data = [
                'title' => '缺少车辆类型信息',
                'content' => '没有车辆类型信息，车辆型号需要车辆类型作为前置条件，请添加车辆类型。',
                'button' => '添加车辆类型',
                'url' => Url::to(['vehicle/add-vehicle-type'])
            ];

            return $this->render('/workshop/empty', ['data' => $data]);
        } else {
            $vehicleTypeList = ArrayHelper::map($vehicleType, 'id', 'name');
        }

        return $this->render('add-model',['model' => $model, 'qualityType' => $qualityTypeList, 'vehicleType' => $vehicleTypeList]);

    }

    /**
     * 编辑车辆型号
     * @return string
     * @author: liuFangShuo
     */
    public function actionEditVehicleModel()
    {

        $id = Yii::$app->request->get('id');
        $model = VehicleModelAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['vehicle/vehicle-model']));
        }
        $model->setScenario('update');

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveModel()) {
                    //成功跳转
                    return $this->redirect(Url::to(['vehicle/vehicle-model']));
                }
            }

            $model->getErrors();

        }


        //获取车辆类型
        $vehicleModel = new VehicleModel();
        $vehicleType = $vehicleModel->getVehicleType();
        $vehicleTypeList = ArrayHelper::map($vehicleType, 'id', 'name');


        //获取质检流程
        $qualityModel = new QualityModel();
        $qualityType = $qualityModel->getQualityType();
        $qualityTypeList = ArrayHelper::map($qualityType,'id','name');

        return $this->render('edit-model', ['model' => $model, 'qualityType' => $qualityTypeList, 'vehicleType' => $vehicleTypeList]);
    }


    /**
     * 车辆类别
     * @author: liuFangShuo
     */
    public function actionVehicleType()
    {
        return $this->render('type');
    }

    /**
     * 获取车辆类别
     * @return object
     * @author: liuFangShuo
     */
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

    /**
     * 添加车辆类别
     * @return string|\yii\web\Response
     * @author: liuFangShuo
     */
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

    /**
     * 编辑车辆类别
     * @return string|\yii\web\Response
     * @author: liuFangShuo
     */
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
