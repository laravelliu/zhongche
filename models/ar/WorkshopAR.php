<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_workshop".
 *
 * @property int $id 车间id
 * @property string $name 车间名称
 * @property string $code 编号
 * @property int $pid 上一车间id
 * @property int $sort 位置
 * @property int $is_deleted 是否删除
 * @property int $create_time
 * @property int $update_time
 */
class WorkshopAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_workshop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sort'], 'required'],
            [['pid', 'sort', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 16],
            [['code'], 'string', 'max' => 32],
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
            'code' => 'Code',
            'pid' => 'Pid',
            'sort' => 'Sort',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
