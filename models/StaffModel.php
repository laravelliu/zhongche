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
use app\models\ar\UserGroupAR;
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
}