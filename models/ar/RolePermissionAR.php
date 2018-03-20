<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_role_permission".
 *
 * @property int $id 角色权限关系表
 * @property int $permission_id 权限id
 * @property int $role_id 角色ID
 * @property int $update_time
 * @property int $create_time
 */
class RolePermissionAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_role_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permission_id', 'role_id', 'update_time', 'create_time'], 'required'],
            [['permission_id', 'role_id', 'update_time', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'permission_id' => 'Permission ID',
            'role_id' => 'Role ID',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
