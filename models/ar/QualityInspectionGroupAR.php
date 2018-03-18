<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_quality_inspection_group".
 *
 * @property int $id 质检组
 * @property string $name 质检组名称
 * @property int $type_id 质检类别ID
 * @property int $is_deleted 是否删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $item_type 是否有工位（1：有 0：没有）
 */
class QualityInspectionGroupAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_quality_inspection_group';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['name', 'type_id', 'item_type'],
            'update' => ['name', 'type_id', 'item_type'],
        ];

        return  array_merge($parent,$self);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'required', 'on' => 'default'],
            [['type_id','is_deleted'], 'integer', 'on' => 'default'],
            [['name'], 'string', 'max' => 64, 'on' => 'default'],
            [['name', 'type_id','item_type'], 'required', 'message' => '不能为空', 'on' =>['create','update']],
            ['item_type', 'in', 'range' => array_keys(Yii::$app->params['quality_item_type']), 'message'=> '不在指定范围', 'on' =>['create', 'update']]
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
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'item_type' => 'Item Type',
        ];
    }

    /**
     * 保存质检组
     * @author: liuFangShuo
     */
    public function saveGroup()
    {
        //查找一下
        if ($this->getScenario() == 'create') {
            $model = new static();
        }

        if($this->getScenario() == 'update') {
            $model = static::findOne(['id' => $this->id, 'is_deleted' => STATUS_FALSE]);

            if (empty($model)) {
                $this->addError('name','不存在此id');
                return false;
            }
        }

        $model->name = $this->name;
        $model->type_id = $this->type_id;

        if(!$model->save(false)){
            $this->addError('name', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}
