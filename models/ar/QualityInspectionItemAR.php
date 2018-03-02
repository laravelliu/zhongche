<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_quality_inspection_item".
 *
 * @property int $id 问题
 * @property int $pid 一个问题分为俩个问题
 * @property string $title 问题描述
 * @property string $standard 参考标准可以为json
 * @property int $type 0为判断题，1为选择题，2为填空题
 * @property int $is_deleted
 * @property int $create_time
 * @property int $update_time
 */
class QualityInspectionItemAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_quality_inspection_item';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['title', 'standard', 'type'],
            'update' => ['title', 'standard', 'type'],
        ];

        return  array_merge($parent,$self); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'is_deleted'], 'integer', 'on'=>['default']],
            [['title', 'standard'], 'string', 'max' => 255, 'on' => ['default', 'create', 'update']],
            [['title', 'type'], 'required', 'message'=>'不能为空', 'on'=>['create', 'update']],
            ['standard' , 'default', 'value' => '', 'on'=>['create','update']],
            ['type','in', 'range' => array_keys(Yii::$app->params['quality_type']), 'message'=>'不在规定范围', 'on'=>'create']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'title' => 'Title',
            'standard' => 'Standard',
            'type' => 'Type',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * 保存质检项
     * @author: liuFangShuo
     */
    public function saveQuality()
    {
        //查找一下
        if ($this->getScenario() == 'create') {
            $model = new static();

        }

        if($this->getScenario() == 'update') {
            $model = static::findOne(['id' => $this->id, 'is_deleted' => STATUS_FALSE]);

            if (empty($model)) {
                $this->addError('title','不存在此id');
                return false;
            }
        }

        $model->title = $this->title;
        $model->pid = 0;

        //是否为多条件
        if (!empty($this->standard)) {
            $standard = explode(';', $this->standard);
            if(empty($standard[count($standard)-1])){
                unset($standard[count($standard)-1]);
            }

            $model->standard = json_encode($standard);
        }

        $model->type = $this->type;

        if(!$model->save(false)){
            $this->addError('code', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }
}
