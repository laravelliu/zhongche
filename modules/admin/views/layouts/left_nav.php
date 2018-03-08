<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 16:32
 */

use yii\helpers\Url;

$user = $this->params['userInfo'];
$url = Yii::$app->request->getPathInfo();

$a = [
        'admin/admin/index',
];
$b = [
        'admin/workshop/workshop',
        'admin/workshop/add-workshop',
        'admin/workshop/edit-workshop',
        'admin/workshop/work-area',
        'admin/workshop/add-work-area',
        'admin/workshop/edit-work-area',
        'admin/workshop/station',
        'admin/workshop/add-station',
        'admin/workshop/edit-station'
];
$c = [
        'admin/quality/quality-process',
        'admin/quality/add-quality-process',
        'admin/quality/edit-quality-process'
];
$d = [
        'admin/quality/quality-type',
        'admin/quality/add-quality-type',
        'admin/quality/edit-quality-type',
        'admin/quality/index',
        'admin/quality/add-quality-item',
        'admin/quality/edit-quality-item',
        'admin/quality/quality-group',
];
$f = [];
$g = [
        'admin/param/car-type',
        'admin/param/add-vehicle-type',
        'admin/param/edit-vehicle-type'
    ];

?>
<?php if(in_array($url,[''])):?>active<?php endif;?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/admin/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?=$user->name?></p>
                <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i>在线</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview <?php if(in_array($url,$a)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-dashboard"></i>
                    <span>人员管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li <?php if(in_array($url,['admin/admin/index'])):?>class="active"<?php endif;?>>
                        <a href="<?=Url::to(['admin/index'])?>"><i class="fa fa-circle-o"></i>人员列表</a>
                    </li>

                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i>员工管理
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?=Url::to(['staff/staff-group'])?>"><i class="fa fa-circle-o"></i>员工组管理</a></li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i>其他管理
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?=Url::to(['department/index'])?>"><i class="fa fa-circle-o"></i>部门管理</a></li>
                            <li><a href="<?=Url::to(['role/index'])?>"><i class="fa fa-circle-o"></i>角色管理</a></li>
                            <li><a href="<?=Url::to(['permission/index'])?>"><i class="fa fa-circle-o"></i>权限管理</a></li>
                            <li><a href="<?=Url::to(['role/distribution'])?>"><i class="fa fa-circle-o"></i>分配权限</a></li>
                        </ul>
                    </li>

                </ul>
            </li>

            <li class="treeview <?php if(in_array($url,$b)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>厂房管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li <?php if(in_array($url, ['admin/workshop/workshop', 'admin/workshop/add-workshop', 'admin/workshop/edit-workshop'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['workshop/workshop'])?>"><i class="fa fa-circle-o"></i>车间管理</a></li>
                    <li <?php if(in_array($url, ['admin/workshop/work-area', 'admin/workshop/add-work-area', 'admin/workshop/edit-work-area'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['workshop/work-area'])?>"><i class="fa fa-circle-o"></i>产线管理</a></li>
                    <li <?php if(in_array($url, ['admin/workshop/station', 'admin/workshop/add-station', 'admin/workshop/edit-station'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['workshop/station'])?>"><i class="fa fa-circle-o"></i>工位管理</a></li>
                </ul>
            </li>
            <li class="treeview <?php if(in_array($url,$c)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-edit"></i>
                    <span>流程管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li <?php if(in_array($url, ['admin/quality/quality-process', 'admin/quality/add-quality-process', 'admin/quality/edit-quality-process'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/quality-process'])?>"><i class="fa fa-circle-o"></i>质检流程管理</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-table"></i> <span>质检任务管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../tables/simple.html"><i class="fa fa-circle-o"></i>质检任务</a></li>
                </ul>
            </li>
            <li class="treeview <?php if(in_array($url,$d)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-table"></i> <span>质检管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li <?php if(in_array($url, ['admin/quality/quality-type', 'admin/quality/add-quality-type', 'admin/quality/edit-quality-type'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/quality-type'])?>"><i class="fa fa-circle-o"></i>质检类别管理</a></li>
                    <li <?php if(in_array($url, ['admin/quality/quality-group'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/quality-group'])?>"><i class="fa fa-circle-o"></i>质检项组管理</a></li>
                    <li><a href="../tables/data.html"><i class="fa fa-circle-o"></i>工位质检项组管理</a></li>
                    <li <?php if(in_array($url, ['admin/quality/index', 'admin/quality/add-quality-item', 'admin/quality/edit-quality-item'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['quality/index'])?>"><i class="fa fa-circle-o"></i>质检项管理</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-table"></i><span>配置管理</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i>配置质检类别拥有产线</a></li>
                </ul>
            </li>

            <li class="treeview <?php if(in_array($url, $g)):?>active<?php endif;?>">
                <a href="#">
                    <i class="fa fa-table"></i><span>车辆管理</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=Url::to(['vehicle/index'])?>"><i class="fa fa-circle-o"></i>车辆信息列表</a></li>
                    <li><a href="<?=Url::to(['vehicle/vehicle-type'])?>"><i class="fa fa-circle-o"></i>车辆类型</a></li>
                    <li <?php if(in_array($url, ['admin/param/car-type', 'admin/param/add-vehicle-type', 'admin/param/edit-vehicle-type'])):?>class="active"<?php endif;?>><a href="<?=Url::to(['vehicle/vehicle-model'])?>"><i class="fa fa-circle-o"></i>车辆型号</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>质检统计</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                    <li><a href="../UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                    <li><a href="../UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                    <li><a href="../UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                    <li><a href="../UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                    <li><a href="../UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
                </ul>
            </li>



            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>参数管理</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                    <li><a href="register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                    <li><a href="lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                    <li><a href="404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                    <li><a href="500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                    <li><a href="blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                    <li><a href="pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
