<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_permission".
 *
 * @property int $id 权限表
 * @property string $name 名称
 * @property string $display_name 描述
 * @property int $level 权限级别
 * @property int $parent_id 上一级id
 * @property int $is_deleted
 * @property int $create_time
 * @property int $update_time
 */
class PermissionAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_permission';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['name', 'display_name', 'parent_id'],
            'update' => ['name', 'display_name', 'parent_id'],
        ];

        return  array_merge($parent,$self); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'parent_id'], 'integer', 'on' => 'default'],
            [['parent_id'], 'required', 'on' => 'default'],
            [['name'], 'string', 'max' => 32, 'on' => 'default'],
            [['display_name'], 'string', 'max' => 255, 'on' => 'default'],
            [['name', 'display_name', 'parent_id'], 'required', 'message' => '不能为空', 'on' => ['create', 'update']],
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
            'display_name' => 'Display Name',
            'level' => 'Level',
            'parent_id' => 'Parent ID',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function savePermission()
    {
        //查找一下
        $model = static::findOne(['name' => $this->name, 'is_deleted' => STATUS_FALSE]);

        //创建时必须不能存在
        if($this->getScenario() == 'create' && !empty($model)){
            $this->addError('name', '权限路径已存在');
            return false;
        }

        //更新时必须存在
        if($this->getScenario() == 'update' && empty($model)){
            $this->addError('name', '权限路径不存在');
            return false;
        }

        if(empty($model)){
            $model = new static();
        }

        if(!empty($this->parent_id )){
            //查找一下上一级车间
            $pws = static::findOne(['id' => $this->parent_id , 'is_deleted' => STATUS_FALSE]);
            $model->level = $pws->level + 1;
        } else {
            $model->level = 1;
        }

        $model->name = $this->name;
        $model->display_name = $this->display_name;
        $model->parent_id  = $this->parent_id ;

        if(!$model->save(false)){
            $this->addError('name', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}
