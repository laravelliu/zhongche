<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_quality_group_process".
 *
 * @property int $id 质检项组所需流程  ID
 * @property int $quality_group_id 质检项组ID
 * @property int $process_id 质检流程ID
 * @property int $create_time
 * @property int $update_time
 */
class QualityGroupProcessAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_quality_group_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quality_group_id', 'process_id', 'create_time', 'update_time'], 'required'],
            [['quality_group_id', 'process_id', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quality_group_id' => 'Quality Group ID',
            'process_id' => 'Process ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
