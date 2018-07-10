<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/6/26
 * Time: 17:51
 */

namespace app\commands;

use Yii;
use app\models\ar\PermissionAR;
use app\models\ar\RolePermissionAR;
use yii\console\Controller;

class InitController extends Controller
{
    protected $rules = [
        '/admin'=>'后台首页',
        '/admin/admin/index' => '人员列表',
        '/admin/admin/get-users' => '获取人员列表-ajax',
        '/admin/admin/distribution-role'=>'人员分配角色',
        '/admin/admin/save-role' =>'保存人员分配角色信息',
        '/admin/admin/edit-user' =>'编辑人员',

        '/admin/department/index' =>'部门管理',
        '/admin/department/department-list' =>'获取部门列表-ajax',
        '/admin/department/add-department' =>'添加部门',
        '/admin/department/edit-department' =>'编辑部门',

        '/admin/index/index' =>'后台首页-全路径',

        '/admin/permission/index' =>'权限列表',
        '/admin/permission/get-permission' =>'获取权限-ajax',
        '/admin/permission/add-permission' =>'添加权限',
        '/admin/permission/edit-permission' =>'编辑权限',

        '/admin/quality/index' =>'质检项列表',
        '/admin/quality/add-quality-item' =>'添加质检项',
        '/admin/quality/get-quality-item' =>'获取质检项-ajax',
        '/admin/quality/edit-quality-item' =>'编辑质检项',
        '/admin/quality/quality-type' =>'质检类型列表',
        '/admin/quality/quality-type-list' =>'获取质检类型-ajax',
        '/admin/quality/add-quality-type' =>'添加质检类型',
        '/admin/quality/edit-quality-type' =>'编辑质检类型',
        '/admin/quality/quality-group' =>'质检项组列表',
        '/admin/quality/get-quality-group' =>'获取质检项组-ajax',
        '/admin/quality/edit-quality-group' =>'编辑质检项组',
        '/admin/quality/add-quality-group' =>'添加质检项组',
        '/admin/quality/quality-process' =>'质检流程列表',
        '/admin/quality/get-quality-process' =>'获取质检流程-ajax',
        '/admin/quality/add-quality-process' =>'添加质检流程',
        '/admin/quality/edit-quality-process' =>'编辑质检流程',
        '/admin/quality/task' =>'质检任务列表',
        '/admin/quality/get-task-list' =>'获取质检任务-ajax',
        '/admin/quality/task-info' =>'质检任务详情',
        '/admin/quality/add-item' =>'质检项组分配质检项',
        '/admin/quality/save-item' =>'保存质检项组分配质检项',
        '/admin/quality/distribution-area' =>'质检类型配置工区',
        '/admin/quality/post-type-work-area' =>'保存质检类型配置工区',
        '/admin/quality/do-job-station' =>'生成职能工位',
        '/admin/quality/job-station' =>'职能工位列表',
        '/admin/quality/get-job-station' =>'获取职能工位-ajax',
        '/admin/quality/edit-job-station' =>'编辑职能工位',
        '/admin/quality/distribution-process' =>'职能工位分配质检流程',
        '/admin/quality/save-job-process' =>'保存职能工位分配质检流程',
        '/admin/quality/distribution-item' =>'职能工位分配质检项',
        '/admin/quality/get-item' =>'质检项组获取质检项-ajax',
        '/admin/quality/save-job-item' =>'保存职能工位',
        '/admin/quality/relate-station' =>'职能工位关联物理工位',
        '/admin/quality/edit-relate-station' =>'编辑职能工位关联物理工位',
        '/admin/quality/group-distribution-process' =>'质检项组分配质检流程',
        '/admin/quality/save-group-process' =>'保存质检项组分配质检流程',
        '/admin/quality/task-info-detail' =>'获取任务详情-ajax',

        '/admin/role/index' =>'角色列表',
        '/admin/role/get-role' =>'获取角色-ajax',
        '/admin/role/add-role' =>'添加角色',
        '/admin/role/edit-role' =>'编辑角色',
        '/admin/role/distribution' =>'角色分配权限',
        '/admin/role/save-role' =>'保存角色分配权限',

        '/admin/staff/staff-group' =>'员工组列表',
        '/admin/staff/group-list' =>'获取员工组-ajax',
        '/admin/staff/add-group' =>'添加员工组',
        '/admin/staff/edit-staff-group' =>'编辑员工组',

        '/admin/user-info/index' =>'个人信息',
        '/admin/user-info/save-info' =>'保存个人信息',
        '/admin/user-info/change-photo' =>'更改个人信息头像',

        '/admin/vehicle/index' =>'车辆列表',
        '/admin/vehicle/get-vehicle-info' =>'获取车辆',
        '/admin/vehicle/vehicle-model' =>'车辆型号列表',
        '/admin/vehicle/get-vehicle-model' =>'获取车辆型号',
        '/admin/vehicle/add-vehicle-model' =>'添加车辆型号',
        '/admin/vehicle/edit-vehicle-model' =>'编辑车辆型号',
        '/admin/vehicle/vehicle-type' =>'车辆类型',
        '/admin/vehicle/get-vehicle-type' =>'获取车辆类型-ajax',
        '/admin/vehicle/add-vehicle-type' =>'添加车辆类型',
        '/admin/vehicle/edit-vehicle-type' =>'编辑车辆类型',

        '/admin/workshop/workshop' =>'车间列表',
        '/admin/workshop/get-workshop' =>'获取车间-ajax',
        '/admin/workshop/add-workshop' =>'添加车间',
        '/admin/workshop/edit-workshop' =>'编辑车间',
        '/admin/workshop/work-area' =>'产线列表',
        '/admin/workshop/get-work-area' =>'获取产线-ajax',
        '/admin/workshop/add-work-area' =>'添加产线',
        '/admin/workshop/edit-work-area' =>'编辑产线',
        '/admin/workshop/station' =>'工位列表',
        '/admin/workshop/get-station' =>'获取工位-ajax',
        '/admin/workshop/add-station' =>'添加工位',
        '/admin/workshop/edit-station' =>'编辑工位',
        '/admin/workshop/get-work-area-list' =>'车间获取产线列表',
        '/admin/workshop/get-station-list' =>'产线获取工位列表',
        '/admin/workshop/user-group-station' =>'工位分配人员组',
        '/admin/workshop/group-station-distribution' =>'保存工位分配人员组',

    ];

    /**
     * @author: liuFangShuo
     * 初始化数据库
     */
    public function actionDbRule()
    {
        //清除数据
        RolePermissionAR::deleteAll();
        PermissionAR::deleteAll();

        $data = [];
        $rules = $this->rules;
        foreach ($rules as $k => $v){
            $data[] =[$k,$v,time(),time()];
        }

        Yii::$app->db->createCommand()->batchInsert(PermissionAR::tableName(), ['name','display_name','create_time', 'update_time'], $data)->execute();

        //将所有权限分配给超级管理员
        $permissionList = PermissionAR::find()->select('id')->asArray()->all();
        $perArr = [];
        foreach ($permissionList as $m){
            $perArr[] = [$m['id'],'1',time(),time()];
        }

        Yii::$app->db->createCommand()->batchInsert(RolePermissionAR::tableName(), ['permission_id','role_id','create_time', 'update_time'], $perArr)->execute();

        return 'ok';
    }
}