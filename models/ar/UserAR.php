<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "zc_user".
 *
 * @property int $id id
 * @property string $username 用户名
 * @property string $password_hash 密码
 * @property string $name 员工名称
 * @property string $phone 电话
 * @property int $is_deleted 是否删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property string $auth_key
 * @property string $password_reset_token
 */
class UserAR extends \app\models\ar\BaseAr implements \yii\web\IdentityInterface
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
            [['is_deleted', 'create_time', 'update_time'], 'integer'],
            [['username'], 'string', 'max' => 128],
            [['password_hash', 'auth_key'], 'string', 'max' => 32],
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
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return UserAR::findOne(['id'=>$id, 'is_deleted' => STATUS_FALSE]);
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return UserAR::findOne(['access_token'=>$token, 'is_deleted' => STATUS_FALSE]);

        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * @param $username
     * @return null|static
     * @author: liuFangShuo
     */
    public static function findByUsername($username)
    {
        return UserAR::findOne(['username' => $username, 'is_deleted' => STATUS_FALSE]);


        /*foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;*/
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
