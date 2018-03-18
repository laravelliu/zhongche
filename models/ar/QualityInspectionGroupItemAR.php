<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_quality_inspection_group_item".
 *
 * @property int $id 质检组和质检项关系
 * @property int $group_id 质检组id
 * @property int $item_id 质检项ID
 * @property int $create_time
 * @property int $update_time
 */
class QualityInspectionGroupItemAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_quality_inspection_group_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'item_id', 'create_time', 'update_time'], 'required'],
            [['group_id', 'item_id', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'item_id' => 'Item ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
