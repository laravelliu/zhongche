<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_task".
 *
 * @property int $id 任务id
 * @property int $status 状态 1入厂鉴定已结束，2代表车间中，3代表整体质检验收
 * @property int $station_id 只有状态为2时有对应的工位ID，当前工位ID，-1为专检、-2为建造
 * @property int $work_area_id 车间ID
 * @property int $vehicle_id 车辆id
 * @property int $type_id 检查种类
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
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
            [['status', 'station_id', 'work_area_id', 'vehicle_id', 'type_id', 'create_time', 'update_time'], 'integer'],
            [['work_area_id', 'vehicle_id', 'type_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'station_id' => 'Station ID',
            'work_area_id' => 'Work Area ID',
            'vehicle_id' => 'Vehicle ID',
            'type_id' => 'Type ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
