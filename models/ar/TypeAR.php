<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_type".
 *
 * @property int $id 质检类别
 * @property string $name 质检名称
 * @property int $is_deleted 是否删除
 * @property int $create_time
 * @property int $update_time
 */
class TypeAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_type';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['name'],
            'update' => ['name'],
        ];

        return  array_merge($parent,$self);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_deleted'], 'integer', 'on' => ['default']],
            [['name'], 'string', 'max' => 128, 'on' => ['default']],
            [['name'], 'required', 'message' => '不能为空', 'on' => ['create', 'update']],
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
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * 保存质检类别
     * @author: liuFangShuo
     */
    public function saveType()
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

        if(!$model->save(false)){
            $this->addError('name', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }

}
