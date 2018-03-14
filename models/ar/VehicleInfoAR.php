<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_vehicle_info".
 *
 * @property int $id 车辆信息id
 * @property string $plate 车辆牌照
 * @property double $weight 自身重量（单位吨）
 * @property double $full_weight 满载重量（单位吨）
 * @property int $vehicle_type_id 车辆类别
 * @property int $vehicle_model_id 车辆型号
 * @property int $is_deleted 是否删除
 * @property int $create_time
 * @property int $update_time
 */
class VehicleInfoAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_vehicle_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight', 'full_weight', 'vehicle_type_id'], 'required'],
            [['weight', 'full_weight'], 'number'],
            [['vehicle_type_id', 'vehicle_model_id', 'is_deleted'], 'integer'],
            [['plate'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plate' => 'Plate',
            'weight' => 'Weight',
            'full_weight' => 'Full Weight',
            'vehicle_type_id' => 'Vehicle Type ID',
            'vehicle_model_id' => 'Vehicle Model ID',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
