<?php

namespace app\models\ar;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "zc_user".
 *
 * @property int $id id
 * @property string $username 用户名
 * @property string $password_hash 密码
 * @property string $name 员工名称
 * @property string $phone 电话
 * @property string $access_token token
 * @property string $auth_key
 * @property string $password_reset_token 重置token
 * @property int $department_id 部门id
 * @property int $group_id 0为不存在工作组，其他为有工作组的概念
 * @property int $is_admin 是否能登录后台
 * @property int $is_deleted 是否删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class UserAR extends BaseAr implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department_id'], 'required'],
            [['department_id', 'group_id', 'is_admin', 'is_deleted', 'create_time', 'update_time'], 'integer'],
            [['username'], 'string', 'max' => 128],
            [['password_hash', 'access_token', 'auth_key'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 16],
            [['phone'], 'string', 'max' => 12],
            [['password_reset_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'name' => 'Name',
            'phone' => 'Phone',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'department_id' => 'Department ID',
            'group_id' => 'Group ID',
            'is_admin' => 'Is Admin',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return UserAR::findOne(['id'=>$id, 'is_deleted' => STATUS_FALSE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return UserAR::findOne(['access_token'=>$token, 'is_deleted' => STATUS_FALSE]);

    }

    /**
     * @param $username
     * @return null|static
     * @author: liuFangShuo
     */
    public static function findByUsername($username)
    {
        return UserAR::findOne(['username' => $username, 'is_deleted' => STATUS_FALSE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password_hash === md5($password);
    }
}
