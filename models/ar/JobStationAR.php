<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_job_station".
 *
 * @property int $id 职能工位id
 * @property string $name 虚拟工位名称（职能工位）
 * @property int $type_id 质检类别id
 * @property int $workshop_id 所属车间
 * @property int $sid 下一工位ID
 * @property int $pid 上一工位id
 * @property int $is_deleted 是否删除
 * @property int $create_time
 * @property int $update_time
 */
class JobStationAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_job_station';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'workshop_id', 'create_time', 'update_time'], 'required'],
            [['type_id', 'workshop_id', 'sid', 'pid', 'is_deleted', 'create_time', 'update_time'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type_id' => 'Type ID',
            'workshop_id' => 'Workshop ID',
            'sid' => 'Sid',
            'pid' => 'Pid',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
