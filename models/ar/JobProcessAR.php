<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_job_process".
 *
 * @property int $id 工位流程所需要的质检流程
 * @property int $job_station_id 职能工位Id
 * @property int $process_id 流程id
 * @property int $create_time
 * @property int $update_time
 */
class JobProcessAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_job_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_station_id', 'process_id', 'create_time', 'update_time'], 'required'],
            [['job_station_id', 'process_id', 'create_time', 'update_time'], 'integer'],
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
            'process_id' => 'Process ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
