<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_answer".
 *
 * @property int $id 质检结果
 * @property int $process_id 质检流程(自检、互检、专检、监造)
 * @property int $item_type 质检项组类型（1、入厂鉴定；2、工位；3、整车质检）
 * @property int $quality_item_id 质检项ID
 * @property string $quality_item_name 质检项名称（冗余质检名称）
 * @property string $quality_item_type 0为判断题，1为选择题，2为填空题 ，3为多选题，4为混合题（混合题需要先判断再填空）
 * @property string $content 所填答案（自定义答案）
 * @property string $choose_content 针对选择题判断（选择的答案）
 * @property string $choose_index 选择题答案
 * @property int $user_id 操作人ID（注意不是工位长）
 * @property string $user_name 操作人名称
 * @property int $task_id 任务ID
 * @property int $analyse_dispatch_job_station_id 分解员指定自定义任务时，任务需执行的职能工位
 * @property int $job_station_id 职能工位id
 * @property int $station_id 物理工位ID
 * @property int $work_area_id 产线ID
 * @property int $workshop_id 车间ID
 * @property int $times 质检次数
 * @property int $status 答案状态 0代表失败，1代表成功
 * @property int $create_time
 * @property int $update_time
 */
class AnswerAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['process_id', 'item_type', 'quality_item_id', 'user_id', 'task_id', 'analyse_dispatch_job_station_id', 'job_station_id', 'station_id', 'work_area_id', 'workshop_id', 'times', 'status', 'create_time', 'update_time'], 'integer'],
            [['quality_item_id', 'user_id'], 'required'],
            [['content', 'choose_content', 'choose_index'], 'string'],
            [['quality_item_name'], 'string', 'max' => 255],
            [['quality_item_type'], 'string', 'max' => 20],
            [['user_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'process_id' => 'Process ID',
            'item_type' => 'Item Type',
            'quality_item_id' => 'Quality Item ID',
            'quality_item_name' => 'Quality Item Name',
            'quality_item_type' => 'Quality Item Type',
            'content' => 'Content',
            'choose_content' => 'Choose Content',
            'choose_index' => 'Choose Index',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'task_id' => 'Task ID',
            'analyse_dispatch_job_station_id' => 'Analyse Dispatch Job Station ID',
            'job_station_id' => 'Job Station ID',
            'station_id' => 'Station ID',
            'work_area_id' => 'Work Area ID',
            'workshop_id' => 'Workshop ID',
            'times' => 'Times',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
