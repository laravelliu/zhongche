<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_job_station_relate_station".
 *
 * @property int $id 职能工位对工位的ID
 * @property int $job_station_id 职能工位
 * @property int $type_id 质检类型
 * @property int $workshop_id 车间ID
 * @property int $work_area_id 工区ID
 * @property int $station_id 工位ID
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class JobStationRelateStationAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_job_station_relate_station';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_station_id', 'type_id', 'workshop_id', 'work_area_id', 'station_id', 'create_time', 'update_time'], 'required'],
            [['job_station_id', 'type_id', 'workshop_id', 'work_area_id', 'station_id', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_station_id' => 'Job Station ID',
            'type_id' => 'Type ID',
            'workshop_id' => 'Workshop ID',
            'work_area_id' => 'Work Area ID',
            'station_id' => 'Station ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
