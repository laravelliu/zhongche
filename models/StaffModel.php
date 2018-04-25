<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/27
 * Time: 17:14
 */

namespace app\models;


use app\models\ar\DepartmentAR;
use app\models\ar\PermissionAR;
use app\models\ar\RoleAR;
use app\models\ar\RolePermissionAR;
use app\models\ar\UserAR;
use app\models\ar\UserGroupAR;
use app\models\ar\UserRoleAR;
use yii\base\Model;
use Yii;
use yii\web\NotFoundHttpException;

class StaffModel extends Model
{
    /**
     * 获取部门信息
     * @author: liuFangShuo
     */
    public function getDepartmentList()
    {
        $department = DepartmentAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $department;
    }

    /**
     * 获取员工组
     * @author: liuFangShuo
     */
    public function getStaffGroup()
    {
        $staffGroup = UserGroupAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $staffGroup;
    }

    /**
     * @author: liuFangShuo
     */
    public function getRole()
    {
        $role = RoleAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $role;
    }

    public function getRoleById(string $id){
        return RoleAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
    }

    /**
     * 获取权限
     * @author: liuFangShuo
     */
    public function getPermission()
    {
        $permission = PermissionAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $permission;
    }

    /**
     * 根据角色获取权限列表
     * @param string $id
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getPermissionByRoleId(string $id)
    {
        $permission = RolePermissionAR::find()->where(['role_id' => $id])->asArray()->all();
        return $permission;
    }

    /**
     * 保存角色的权限
     * @param $id
     * @param array $select
     * @param array $delete
     * @return bool
     * @author: liuFangShuo
     */
    public function saveRolePermission($id, $select = [], $delete = [])
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            $role = $this->getRoleById($id);

            if(empty($role)){
                throw new NotFoundHttpException('角色不存在');
            }

            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_role_permission where role_id=$id and permission_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = RolePermissionAR::find()->where(['role_id' => $id, 'permission_id' => $select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'permission_id');
                }

                $diff = array_diff($select, $haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(RolePermissionAR::tableName(), ['role_id', 'permission_id', 'create_time', 'update_time'], $arrSelect)->execute();

                    if (!$res) {
                        throw new NotFoundHttpException('新增失败');
                    }
                }

            }

            $trans->commit();
            return true;

        } catch (\Exception $e){
            $trans->rollBack();
            $this->addError('name', $e->getMessage());
            return false;
        }
    }

    /**
     * 根据用户获取拥有角色
     * @param string $id
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getRoleByUserId(string $id)
    {
        $roleList = UserRoleAR::find()->where(['user_id' => $id])->asArray()->all();
        return $roleList;
    }

    /**
     * 根据用户id获取用户信息
     * @param string $id
     * @return null|static
     * @author: liuFangShuo
     */
    public function getUserById(string $id)
    {
        $userInfo = UserAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
        return $userInfo;
    }


    /**
     * 保存用户拥有的角色
     * @param $id
     * @param array $select
     * @param array $delete
     * @return bool
     * @author: liuFangShuo
     */
    public function saveUserRole($id, $select = [], $delete = [])
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            $user = $this->getUserById($id);

            if(empty($user)){
                throw new NotFoundHttpException('人员不存在');
            }

            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_user_role where user_id=$id and role_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = UserRoleAR::find()->where(['user_id' => $id, 'role_id' => $select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'role_id');
                }

                $diff = array_diff($select, $haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(UserRoleAR::tableName(), ['user_id', 'role_id', 'create_time', 'update_time'], $arrSelect)->execute();

                    if (!$res) {
                        throw new NotFoundHttpException('新增失败');
                    }
                }

            }

            $trans->commit();
            return true;

        } catch (\Exception $e){
            $trans->rollBack();
            $this->addError('name', $e->getMessage());
            return false;
        }
    }

    /**
     * 判断是否拥有此权限
     * @param $id
     * @param array $role
     * @return bool
     * @author: liuFangShuo
     */
    public function isHaveRole($id,$role = [])
    {
           $return = UserRoleAR::find()->where(['user_id' => $id, 'role_id' => $role])->asArray()->all();

           if(!empty($return)){
               return true;
           }

           return false;
    }

    //判断是否为员工或者员工长
    public function isStaffOrStaffLeader($id)
    {
        return $this->isHaveRole($id,[ROLE_STAFF,ROLE_STAFF_LEADER]);
    }

    /**
     * 判断是否需要车间信息
     * 调度|分解|专检|监造
     * @author: liuFangShuo
     */
    public function isNeedWorkshop($id)
    {
        return $this->isHaveRole($id,[ROLE_DISPATCHER,ROLE_RESOLVE,ROLE_INSPECTION,ROLE_SUPERVISOR]);

    }
}