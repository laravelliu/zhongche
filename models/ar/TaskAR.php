<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_task".
 *
 * @property int $id 任务id
 * @property int $vehicle_id 车辆id
 * @property int $type_id 检查种类
 * @property double $vehicle_weight 车辆自重(单位：吨)
 * @property double $vehicle_full_weight 车辆满载重量(单位：吨)
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $vehicle_model_id 车辆型号id
 * @property int $finish 任务是否完结0：未完结，1：完结，2为终止
 * @property int $user_id 废除人用户ID
 * @property int $deprecated_time 删除时间
 */
class TaskAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'type_id', 'vehicle_weight', 'vehicle_full_weight', 'vehicle_model_id'], 'required'],
            [['vehicle_id', 'type_id', 'create_time', 'update_time', 'user_id', 'deprecated_time', 'vehicle_model_id', 'finish'], 'integer'],
            [['vehicle_weight', 'vehicle_full_weight'], 'number'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vehicle_id' => 'Vehicle ID',
            'type_id' => 'Type ID',
            'vehicle_weight' => 'Vehicle Weight',
            'vehicle_full_weight' => 'Vehicle Full Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'vehicle_model_id' => 'Vehicle Model ID',
            'finish' => 'Finish',
            'user_id' => 'User ID',
            'deprecated_time' => 'Deprecated Time',
        ];
    }
}
