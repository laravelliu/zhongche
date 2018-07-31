<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_task_execute_record_deprecated".
 *
 * @property int $id 主键标识
 * @property int $task_id 任务id
 * @property int $workshop_id 车间id
 * @property int $work_area_id 工区id
 * @property int $job_station_id 职能工位id
 * @property int $type_id 质检类型id
 * @property int $quality_inspection_group_id 质检项组id
 * @property int $quality_inspection_group_type 质检项组类型：1入厂鉴定，2工位质检，3整车质检
 * @property int $process_id 质检级别(zc_process)id(自检、互检、专检、分解、监造等)
 * @property int $user_id 操作此条记录员工id，当为职能工位时，表示操作工位长id
 * @property int $user_check_id 当为职能工位时，表示互检标识
 * @property int $default_work_area 标识同一车间里面选择的默认产线标识0：不是，1：是
 * @property int $execute_status 执行状态-0:未执行，1:执行完成(对于工位长必须自检/互检执行完状态才能为1)
 * @property int $is_doing_random_inspection 是否正在做抽检：0：非；1：是；2：完成抽检了
 * @property string $mark 标识记录
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $deprecated_time 废弃时间
 */
class TaskExecuteRecordDeprecatedAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_task_execute_record_deprecated';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'workshop_id', 'work_area_id', 'job_station_id', 'type_id', 'quality_inspection_group_id', 'quality_inspection_group_type', 'process_id', 'user_id', 'user_check_id', 'default_work_area', 'execute_status', 'is_doing_random_inspection', 'create_time', 'update_time', 'deprecated_time'], 'integer'],
            [['task_id', 'type_id'], 'required'],
            [['mark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'workshop_id' => 'Workshop ID',
            'work_area_id' => 'Work Area ID',
            'job_station_id' => 'Job Station ID',
            'type_id' => 'Type ID',
            'quality_inspection_group_id' => 'Quality Inspection Group ID',
            'quality_inspection_group_type' => 'Quality Inspection Group Type',
            'process_id' => 'Process ID',
            'user_id' => 'User ID',
            'user_check_id' => 'User Check ID',
            'default_work_area' => 'Default Work Area',
            'execute_status' => 'Execute Status',
            'is_doing_random_inspection' => 'Is Doing Random Inspection',
            'mark' => 'Mark',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'deprecated_time' => 'Deprecated Time',
        ];
    }
}
