<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 16:32
 */

use yii\helpers\Url;

$user = $this->params['userInfo'];
$url = '/'.Yii::$app->request->getPathInfo();

$a = [
    '/admin/staff/staff-group',
    '/admin/staff/add-group',
    '/admin/staff/edit-staff-group',

    '/admin/department/index',
    '/admin/department/add-department',
    '/admin/department/edit-department',

    '/admin/role/index',
    '/admin/role/edit-role',
    '/admin/role/add-role',

    '/admin/permission/index',
    '/admin/permission/add-permission',
    '/admin/permission/edit-permission',

    '/admin/admin/index',
    '/admin/admin/edit-user',
    '/admin/admin/distribution-role',
    '/admin/role/distribution'
];
$a1 = [
    '/admin/staff/staff-group',
    '/admin/staff/add-group',
    '/admin/staff/edit-staff-group',
];
$a2 = [
    '/admin/department/index',
    '/admin/department/add-department',
    '/admin/department/edit-department',

    '/admin/role/index',
    '/admin/role/edit-role',
    '/admin/role/add-role',
    '/admin/role/distribution',

    '/admin/permission/index',
    '/admin/permission/add-permission',
    '/admin/permission/edit-permission'

];
$b = [
    '/admin/workshop/workshop',
    '/admin/workshop/add-workshop',
    '/admin/workshop/edit-workshop',
    '/admin/workshop/work-area',
    '/admin/workshop/add-work-area',
    '/admin/workshop/edit-work-area',
    '/admin/workshop/station',
    '/admin/workshop/add-station',
    '/admin/workshop/edit-station',
    '/admin/workshop/user-group-station'
];
$c = [
    '/admin/quality/quality-process',
    '/admin/quality/add-quality-process',
    '/admin/quality/edit-quality-process'
];
$d = [
    '/admin/quality/quality-type',
    '/admin/quality/add-quality-type',
    '/admin/quality/edit-quality-type',

    '/admin/quality/index',
    '/admin/quality/add-quality-item',
    '/admin/quality/edit-quality-item',

    '/admin/quality/quality-group',
    '/admin/quality/add-quality-group',
    '/admin/quality/edit-quality-group',

    '/admin/quality/add-item',


    '/admin/quality/job-station',
    '/admin/quality/edit-job-station',
    '/admin/quality/distribution-process',
    '/admin/quality/distribution-item',

    '/admin/quality/group-distribution-process',
    '/admin/quality/distribution-area',
    '/admin/quality/relate-station',

    '/admin/quality/distribution-area'

];
$f = [
    '/admin/quality/task',
    '/admin/quality/task-info'
];
$g = [
    '/admin/vehicle/index',
    '/admin/vehicle/vehicle-model',
    '/admin/vehicle/add-vehicle-model',
    '/admin/vehicle/edit-vehicle-model',
    '/admin/vehicle/vehicle-type',
    '/admin/vehicle/add-vehicle-type',
    '/admin/vehicle/edit-vehicle-type',
];


$pathArr = $user->getUserPermission();

?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=$user->admin_photo?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?=$user->name?></p>
                <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i>在线</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li <?php if(in_array($url,['/admin/index/index','/admin'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['index/index'])?>"><i class="fa fa-dashboard"></i><span>控制台</span></a></li>

            <?php if(!empty(array_intersect($pathArr,$a))):?>
            <li class="treeview <?php if(in_array($url,$a)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-address-book"></i>
                    <span>人员管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array('/admin/admin/index', $pathArr)):?>
                    <li <?php if(in_array($url,['/admin/admin/index', '/admin/admin/edit-user', '/admin/admin/distribution-role'])):?>class="active"<?php endif;?>>
                        <a href="<?=Url::to(['admin/index'])?>"><i class="glyphicon glyphicon-user"></i>人员列表</a>
                    </li>
                    <?php endif;?>

                    <?php if(in_array('/admin/staff/staff-group', $pathArr)):?>
                    <li <?php if(in_array($url,['/admin/staff/staff-group','/admin/staff/add-group','/admin/staff/edit-staff-group'])):?>class="active"<?php endif;?>>
                        <a href="<?=Url::to(['staff/staff-group'])?>"><i class="fa fa-group"></i>员工组管理</a>
                    </li>
                    <?php endif;?>

                    <?php if(!empty(array_intersect($pathArr,$a2))):?>
                    <li class="treeview <?php if(in_array($url,$a2)):?>menu-open<?php endif?>">
                        <a href="#"><i class="fa fa-ellipsis-h"></i>其他管理
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" <?php if(in_array($url,$a2)):?>style="display: block;"<?php endif;?>>

                            <?php if(in_array('/admin/department/index', $pathArr)):?>
                            <li <?php if(in_array($url,['/admin/department/index','/admin/department/add-department','/admin/department/edit-department'])):?>class="active"<?php endif;?>>
                                <a href="<?=Url::to(['department/index'])?>"><i class="fa fa-sitemap"></i>部门管理</a>
                            </li>
                            <?php endif;?>

                            <?php if(in_array('/admin/role/index', $pathArr)):?>
                            <li <?php if(in_array($url,['/admin/role/index','/admin/role/edit-role','/admin/role/add-role', '/admin/role/distribution'])):?>class="active"<?php endif;?>>
                                <a href="<?=Url::to(['role/index'])?>"><i class="fa fa-address-card"></i>角色管理</a>
                            </li>
                            <?php endif;?>

                            <?php if(in_array('/admin/permission/index', $pathArr)):?>
                            <li <?php if(in_array($url,['/admin/permission/index','/admin/permission/add-permission','/admin/permission/edit-permission'])):?>class="active"<?php endif;?>>
                                <a href="<?=Url::to(['permission/index'])?>"><i class="fa fa-folder-open"></i>权限管理</a>
                            </li>
                            <?php endif;?>
                        </ul>
                    </li>
                    <?php endif;?>

                </ul>
            </li>
            <?php endif;?>

            <?php if(!empty(array_intersect($pathArr,$b))):?>
            <li class="treeview <?php if(in_array($url,$b)):?>active<?php endif;?>">
                <a href="#">
                    <i class="glyphicon glyphicon-home"></i>
                    <span>厂房管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array('/admin/workshop/workshop', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/workshop/workshop', '/admin/workshop/add-workshop', '/admin/workshop/edit-workshop'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['workshop/workshop'])?>"><i class="fa fa-home"></i>车间管理</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/workshop/work-area', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/workshop/work-area', '/admin/workshop/add-work-area', '/admin/workshop/edit-work-area'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['workshop/work-area'])?>"><i class="fa fa-road"></i>工区管理</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/workshop/station', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/workshop/station', '/admin/workshop/add-station', '/admin/workshop/edit-station','/admin/workshop/user-group-station'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['workshop/station'])?>"><i class="fa fa-tty"></i>工位管理</a></li>
                    <?php endif;?>
                </ul>
            </li>
            <?php endif;?>

            <?php if(!empty(array_intersect($pathArr,$c))):?>
            <li class="treeview <?php if(in_array($url,$c)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-random"></i>
                    <span>流程管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array('/admin/quality/quality-process', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/quality/quality-process', '/admin/quality/add-quality-process', '/admin/quality/edit-quality-process'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/quality-process'])?>"><i class="fa fa-circle-o"></i>质检流程管理</a></li>
                    <?php endif;?>
                </ul>
            </li>
            <?php endif;?>

            <?php if(!empty(array_intersect($pathArr,$f))):?>
            <li class="treeview <?php if(in_array($url,$f)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-tasks"></i> <span>质检任务管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array('/admin/quality/task', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/quality/task','/admin/quality/task-info'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/task'])?>"><i class="fa fa-circle-o"></i>质检任务</a></li>
                    <?php endif;?>
                </ul>
            </li>
            <?php endif;?>

            <?php if(!empty(array_intersect($pathArr,$d))):?>
            <li class="treeview <?php if(in_array($url,$d)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-table"></i> <span>质检管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array('/admin/quality/quality-type', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/quality/quality-type', '/admin/quality/add-quality-type', '/admin/quality/edit-quality-type', '/admin/quality/distribution-area'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/quality-type'])?>"><i class="fa fa-circle-o"></i>质检类型管理</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/quality/quality-group', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/quality/quality-group', '/admin/quality/add-quality-group', '/admin/quality/edit-quality-group', '/admin/quality/add-item', '/admin/quality/group-distribution-process'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/quality-group'])?>"><i class="fa fa-circle-o"></i>质检项组管理</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/quality/index', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/quality/index', '/admin/quality/add-quality-item', '/admin/quality/edit-quality-item'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/index'])?>"><i class="fa fa-circle-o"></i>质检项管理</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/quality/job-station', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/quality/job-station', '/admin/quality/edit-job-station', '/admin/quality/distribution-process', '/admin/quality/distribution-item', '/admin/quality/relate-station'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/job-station'])?>"><i class="fa fa-circle-o"></i>职能工位管理</a></li>
                    <?php endif;?>
                </ul>
            </li>
            <?php endif;?>

            <?php if(!empty(array_intersect($pathArr,$g))):?>
            <li class="treeview <?php if(in_array($url, $g)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-subway"></i><span>车辆管理</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array('/admin/vehicle/index', $pathArr)):?>
                    <li <?php if(in_array($url,['/admin/vehicle/index'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['vehicle/index'])?>"><i class="fa fa-circle-o"></i>车辆信息列表</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/vehicle/vehicle-type', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/vehicle/vehicle-type', '/admin/vehicle/add-vehicle-type', '/admin/vehicle/edit-vehicle-type'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['vehicle/vehicle-type'])?>"><i class="fa fa-circle-o"></i>车辆类型</a></li>
                    <?php endif;?>

                    <?php if(in_array('/admin/vehicle/vehicle-model', $pathArr)):?>
                    <li <?php if(in_array($url, ['/admin/vehicle/vehicle-model', '/admin/vehicle/add-vehicle-model', '/admin/vehicle/edit-vehicle-model'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['vehicle/vehicle-model'])?>"><i class="fa fa-circle-o"></i>车辆型号</a></li>
                    <?php endif;?>
                </ul>
            </li>
            <?php endif;?>

            <!--<li class="treeview">
                <a href="#">
                    <i class="fa fa-area-chart"></i>
                    <span>质检统计</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i>等待需求</a></li>
                </ul>
            </li>-->

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
