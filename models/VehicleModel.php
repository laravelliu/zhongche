<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/8
 * Time: 16:01
 */

namespace app\models;


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
}