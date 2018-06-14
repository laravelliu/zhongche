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
 * @property int $item_type 质检项类型 1入厂鉴定，2工位质检，3整车质检
 * @property int $is_split 是否为分解质检项组 0：否；1：是
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
            'create' => ['name', 'type_id', 'item_type','is_split'],
            'update' => ['name', 'type_id', 'item_type','is_split'],
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
            [['type_id','is_deleted','is_split'], 'integer', 'on' => 'default'],
            [['name'], 'string', 'max' => 64, 'on' => 'default'],
            [['name', 'type_id','item_type', 'is_split'], 'required', 'message' => '不能为空', 'on' =>['create','update']],
            ['item_type', 'in', 'range' => array_keys(Yii::$app->params['quality_item_type']), 'message'=> '不在指定范围', 'on' =>['create', 'update']],
            ['is_split', 'in', 'range' => array_keys(Yii::$app->params['quality_group_need_decomposition']), 'message'=> '不在指定范围', 'on' =>['create', 'update']]
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
            'is_split' => 'Is Split',
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
        $model->item_type = $this->item_type;
        $model->is_split = $this->is_split;

        if(!$model->save(false)){
            $this->addError('name', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}
