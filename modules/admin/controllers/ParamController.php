<?php

namespace app\modules\admin\controllers;

use app\models\ar\ParamAR;
use app\models\ParamModel;
use Yii;
use yii\helpers\Url;

class ParamController extends BaseController
{
    /**
     * 车辆类别参数
     * @return string
     * @author: liuFangShuo
     */
    public function actionCarType()
    {
        return $this->render('car-type');
    }

    /**
     * 获取车辆类别
     * @author: liuFangShuo
     */
    public function actionGetParams()
    {
        $type = Yii::$app->request->post('type',0);

        if(empty($type)){
            return $this->ajaxReturn([],1,'参数不全');
        }

        $model = new ParamModel();
        $params = $model->getParamList($type);

        /**
         *  参数
         */
        if (!empty($params)) {
            foreach ($params as $k => $v){
                $params[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $params[$k]['update_time'] = date('Y-m-d H:i:s',  $v['update_time']);
                $params[$k]['json'] = json_decode($v['json'],true);
            }
        }

        return $this->ajaxReturn($params);
    }

    /**
     * 添加车辆类别
     * @author: liuFangShuo
     */
    public function actionAddVehicleType()
    {
        $model = new ParamAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){
            $model->type = PARAM_TYPE_VEHICLE_TYPE;
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveParam()) {
                    //成功跳转
                    return $this->redirect(Url::to(['param/car-type']));
                }
            }

            $model->getErrors();

        }

        return $this->render('add-vehicle-type',['model' => $model]);
    }

    /**
     * 编辑车辆类别
     * @author: liuFangShuo
     */
    public function actionEditVehicleType()
    {
        $id = Yii::$app->request->get('id',null);
        $type = Yii::$app->request->get('type', null);
        $model = ParamAR::findOne(['id' => $id, 'type' => $type, 'is_deleted' => STATUS_FALSE]);
        $model->setScenario('update');

        if(empty($id) || empty($type) || empty($model)){
            return $this->redirect(Url::to(['param/car-type']));
        }

        $model->json = json_decode($model->json,true);

        if (Yii::$app->request->isPost) {
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveParam()) {
                    //成功跳转
                    return $this->redirect(Url::to(['param/car-type']));
                }
            }

            $model->getErrors();
        }

        return $this->render('edit-vehicle-type',['model' => $model]);
    }


    public function actionVehicleModle()
    {

    }
}
