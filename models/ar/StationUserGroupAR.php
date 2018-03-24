<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_station_user_group".
 *
 * @property int $id 员工工位表
 * @property int $station_id 工位ID
 * @property int $user_group_id 用户组id
 * @property int $update_time
 * @property int $create_time
 */
class StationUserGroupAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_station_user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_id', 'user_group_id', 'update_time', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_id' => 'Station ID',
            'user_group_id' => 'User Group ID',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
