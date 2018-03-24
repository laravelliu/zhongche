<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_type_work_area".
 *
 * @property int $id 质检种类所拥有的产线
 * @property int $work_area_id 物理产线id
 * @property int $type_id 质检类别id
 * @property int $is_standard 是否为标准产线
 * @property int $create_time
 * @property int $update_time
 */
class TypeWorkAreaAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_type_work_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_area_id', 'type_id'], 'required'],
            [['work_area_id', 'type_id', 'is_standard', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_area_id' => 'Work Area ID',
            'type_id' => 'Type ID',
            'is_standard' => 'Is Standard',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
