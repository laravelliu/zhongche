<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/8
 * Time: 16:01
 */

namespace app\models;


use app\models\ar\VehicleInfoAR;
use app\models\ar\VehicleModelAR;
use app\models\ar\VehicleTypeAR;
use yii\base\Model;

class VehicleModel extends Model
{
    /**
     * 获取车辆类别
     * @author: liuFangShuo
     */
    public function getVehicleType()
    {
        $vehicleTypeList = VehicleTypeAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $vehicleTypeList;
    }

    /**
     * 获取车型型号
     * @author: liuFangShuo
     */
    public function getVehicleModel()
    {
        $vehicleModelList = VehicleModelAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $vehicleModelList;
    }

    /**
     * 获取车辆列表
     * @author: liuFangShuo
     */
    public function getVehicleList()
    {
        $vehicleList = VehicleInfoAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $vehicleList;
    }

    /**
     * 根据ID获取车辆信息
     * @param $ids
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getVehicleById($ids)
    {
        $vehicleList = VehicleInfoAR::find()->where(['is_deleted' => STATUS_FALSE, 'id' =>$ids])->asArray()->all();
        return $vehicleList;
    }

}