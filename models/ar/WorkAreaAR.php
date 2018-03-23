<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_work_area".
 *
 * @property int $id 工区id
 * @property string $name 工区名称
 * @property string $code 工区编码
 * @property int $workshop_id 车间id
 * @property int $is_deleted 是否删除
 * @property int $create_time
 * @property int $update_time
 */
class WorkAreaAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_work_area';
    }

    /**
     * 场景
     * @return array
     * @author: liuFangShuo
     */
    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['name', 'code', 'workshop_id'],
            'update' => ['name', 'code', 'workshop_id'],
        ];

        return  array_merge($parent,$self); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'workshop_id'], 'required', 'on' => 'default'],
            [['workshop_id', 'is_deleted'], 'integer', 'on' => 'default'],
            [['name'], 'string', 'max' => 16, 'on' => ['default', 'create', 'update']],
            [['code'], 'string', 'max' => 32, 'on' => ['default', 'create', 'update']],

            [['code', 'name', 'workshop_id'], 'required', 'message' => '不能为空',  'on' => ['create', 'update']],
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
            'workshop_id' => 'Workshop ID',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    //保存工区
    public function saveWorkArea()
    {
        //查找一下
        $model = static::findOne(['code' => $this->code, 'workshop_id' => $this->workshop_id, 'is_deleted' => STATUS_FALSE]);

        //创建时必须不能存在
        if($this->getScenario() == 'create' && !empty($model)){
            $this->addError('code', '工区编号已存在');
            return false;
        }

        //更新时必须存在
        if($this->getScenario() == 'update' && empty($model)){
            $this->addError('name', '工区不存在');
            return false;
        }

        if(empty($model)){
            $model = new static();
        }

        $model->name = $this->name;
        $model->code = $this->code;
        $model->workshop_id = $this->workshop_id;

        //todo::触发修改对应工区下面的车间信息

        if(!$model->save(false)){
            $this->addError('code', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}
