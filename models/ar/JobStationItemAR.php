<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_job_station_item".
 *
 * @property int $id 职能工位所对应的质检项关系表
 * @property int $job_station_id 职能工位ID
 * @property int $item_id 质检项id
 * @property int $create_time
 * @property int $update_time
 */
class JobStationItemAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_job_station_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_station_id', 'item_id', 'create_time', 'update_time'], 'required'],
            [['job_station_id', 'item_id', 'create_time', 'update_time'], 'integer'],
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
            'item_id' => 'Item ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
