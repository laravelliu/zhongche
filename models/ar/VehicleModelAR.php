<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_vehicle_model".
 *
 * @property int $id 车辆型号ID
 * @property string $name 车辆型号
 * @property int $type_id 所需质检类别
 * @property int $vehicle_type_id 所属车辆类别
 * @property int $is_deleted
 * @property int $create_time
 * @property int $update_time
 */
class VehicleModelAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_vehicle_model';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['name', 'type_id', 'vehicle_type_id'],
            'update' => ['name', 'type_id', 'vehicle_type_id'],
        ];

        return  array_merge($parent,$self); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'vehicle_type_id', 'is_deleted'], 'required', 'on' => 'default'],
            [['type_id', 'vehicle_type_id', 'is_deleted'], 'integer', 'on' => 'default'],
            [['name'], 'string', 'max' => 32, 'on' => 'default'],
            [['name', 'type_id', 'vehicle_type_id'], 'required', 'message' => '不能为空', 'on' => ['create', 'update']]
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
            'vehicle_type_id' => 'Vehicle Type ID',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * 保存车辆型号
     * @author: liuFangShuo
     */
    public function saveModel()
    {
        //查找一下
        $model = static::findOne(['name' => trim($this->name), 'vehicle_type_id' => $this->vehicle_type_id, 'is_deleted' => STATUS_FALSE]);

        //创建时必须不能存在
        if($this->getScenario() == 'create' && !empty($model)){
            $this->addError('name', '车辆型号已存在');
            return false;
        }

        //更新时必须存在
        if($this->getScenario() == 'update' && empty($model)){
            $this->addError('name', '车辆型号不存在');
            return false;
        }

        if(empty($model)){
            $model = new static();
        }

        $model->name = $this->name;
        $model->vehicle_type_id = $this->vehicle_type_id;
        $model->type_id = $this->type_id;

        if(!$model->save(false)){
            $this->addError('name', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}
