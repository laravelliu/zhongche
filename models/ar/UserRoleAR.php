<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_user_role".
 *
 * @property int $id 用户角色关系表
 * @property int $user_id 用户id
 * @property int $role_id 角色id
 * @property int $create_time
 * @property int $update_time
 */
class UserRoleAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'create_time', 'update_time'], 'required'],
            [['user_id', 'role_id', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
