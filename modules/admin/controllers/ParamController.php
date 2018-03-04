<?php

namespace app\modules\admin\controllers;

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
    public function actionGetCarType()
    {

        return $this->ajaxReturn();
    }

    /**
     * 添加车辆类别
     * @author: liuFangShuo
     */
    public function actionAddVehicleType()
    {
        return $this->render('add-vehicle-type');
    }
}
