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
 * @property string $email 邮箱
 * @property string $admin_photo 后台头像
 * @property string $access_token token
 * @property string $auth_key
 * @property int $department_id 部门id
 * @property int $group_id 0为不存在工作组，其他为有工作组的概念
 * @property int $workshop_id 车间id：0：标识不存在车间id
 * @property int $is_admin 是否能登录后台
 * @property int $is_deleted 是否删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $last_time 最近登录时间
 */
class UserAR extends BaseAR implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_user';
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $self = [
            'create' => ['username', 'password_hash', 'name', 'email', 'is_admin', 'admin_photo', 'phone'],
            'update' => ['name', 'department_id', 'is_admin'],
            'updateWithGroup' => ['name', 'department_id', 'is_admin', 'group_id'],
            'updateWithWorkshop' => ['name', 'department_id', 'is_admin','workshop_id'],
            'updateInfo' => ['email', 'phone']
        ];

        return  array_merge($parent,$self); // TODO: Change the autogenerated stub

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department_id','workshop_id'], 'required','on'=>'default'],
            [['department_id', 'group_id', 'workshop_id', 'is_admin', 'is_deleted'], 'integer','on'=>'default'],
            [['username'], 'string', 'max' => 128,'on'=>'default'],
            [['password_hash', 'access_token', 'auth_key'], 'string', 'max' => 32,'on'=>'default'],
            [['name'], 'string', 'max' => 16,'on'=>'default'],
            [['phone'], 'string', 'max' => 12,'on'=>'default'],
            [['email'], 'string', 'max' => 255,'on'=>'default'],
            [['name', 'department_id', 'is_admin'], 'required', 'message' => '不能为空', 'on' => ['update','updateWithWorkshop','updateWithGroup']],
            [['group_id'], 'required', 'message' => '不能为空', 'on' =>'updateWithGroup'],
            [['workshop_id'], 'required', 'message' => '不能为空', 'on' =>'updateWithWorkshop'],
            [['email', 'phone'], 'required', 'message' => '不能为空', 'on' => 'updateInfo'],
            [['username', 'name','phone', 'password_hash', 'email', 'is_admin'], 'required', 'message' => '不能为空', 'on' => 'create'],
            ['username', 'unique', 'message'=>'用户已存在', 'on'=>['create']]
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
            'email' => 'Email',
            'admin_photo' => 'Admin Photo',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'department_id' => 'Department ID',
            'group_id' => 'Group ID',
            'workshop_id' => 'Workshop ID',
            'is_admin' => 'Is Admin',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'last_time' => 'Last Time',
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

    /**
     * 保存用户信息
     * @author: liuFangShuo
     */
    public function saveUser()
    {
        if(in_array($this->getScenario(),['update', 'updateWithGroup', 'updateWithWorkshop'])) {
            $model = static::findOne(['id' => $this->id, 'is_deleted' => STATUS_FALSE]);

            if (empty($model)) {
                $this->addError('title','不存在此id');
                return false;
            }
        }

        if($this->getScenario() == 'updateWithGroup'){
            $model->group_id = $this->group_id;
        }

        if($this->getScenario() == 'updateWithWorkshop'){
            $model->workshop_id = $this->workshop_id;
        }

        $model->department_id = $this->department_id;
        $model->is_admin = $this->is_admin;

        if(!$model->save(false)){
            $this->addError('code', '网络问题，稍后重试');
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取用户角色列表
     * @author: liuFangShuo
     */
    public function getUserRoles()
    {
        $cache = Yii::$app->cache;
        $roleList = json_decode($cache->get($this->getId().'roles'),true);

        if (empty($roleList)) {
            $roles = UserRoleAR::findAll(['user_id' => $this->id]);
            $roleList = array_column($roles,'role_id');
            $cache->set($this->getId().'roles',json_encode($roleList),3600);
        }

        return $roleList;
    }


    /**
     * 判断是否为超管
     * @author: liuFangShuo
     */
    public function isSuperAdmin()
    {
        $roles = $this->getUserRoles();
        if(in_array(1,$roles)){
            return true;
        }

        return false;
    }

    /**
     * 获取用户权限
     * @author: liuFangShuo
     */
    public function getUserPermission()
    {
        $cache = Yii::$app->cache;
        $pathArr = json_decode($cache->get($this->getId().'permission'),true);

        if(empty($pathArr)){
            $roleList = $this->getUserRoles();

            if(empty($roleList)){
                return $roleList;
            }

            $roleStr = implode($roleList,',');

            //根据用户查询一下对应的权限
            $sql = "select DISTINCT(c.name) from zc_role_permission as b join zc_permission as c on b.permission_id=c.id where b.role_id in ($roleStr)";
            $connection  = Yii::$app->db;
            $command = $connection->createCommand($sql);
            $res = $command->queryAll();

            $pathArr = array_column($res, 'name');

            $cache->set($this->getId().'permission',json_encode($pathArr),3600);
        }

        return $pathArr;
    }

}
