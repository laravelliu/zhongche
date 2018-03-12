<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_param".
 *
 * @property int $id 参数表ID
 * @property int $type 类别
 * @property string $json 数据
 * @property int $is_deleted 是否删除
 * @property int $create_time
 * @property int $update_time
 */
class ParamAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_param';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['type', 'json'],
            'update' => ['type', 'json'],
        ];

        return  array_merge($parent,$self); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'json'], 'required', 'on'=>'default'],
            [['type','is_deleted'], 'integer', 'on'=>'default'],
            [['json'], 'string', 'max' => 2048, 'on'=>'default'],
            ['type', 'in', 'range' => array_keys(Yii::$app->params['param_type']), 'message'=>'参数不存在', 'on' => ['create', 'update']],
            [['json', 'type'], 'required', 'message' => '不能为空', 'on' => ['create', 'update']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'json' => 'Json',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * 存参数
     * @author: liuFangShuo
     */
    public function saveParam()
    {
        //查找一下
        if ($this->getScenario() == 'create') {
            $model = new static();
        }

        if($this->getScenario() == 'update') {
            $model = static::findOne(['id' => $this->id, 'is_deleted' => STATUS_FALSE]);

            if (empty($model)) {
                $this->addError('json','id不存在');
                return false;
            }
        }

        $model->type = $this->type;
        $model->json = json_encode($this->json);

        if(!$model->save(false)){
            $this->addError('code', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}