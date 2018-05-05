<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2017/4/6
 * Time: 13:51
 */

/******邮箱设置******/
define('EMAIL_SERVICE',1);
define('EMAIL_USER',1);
define('EMAIL_PWD',1);
define('EMAIL_PORT',1);

define('STATUS_TRUE', 1);
define('STATUS_FALSE', 0);

//质检项类别
define('QUALITY_TYPE_JUDGE', 0);    //判断
define('QUALITY_TYPE_CHOOSE', 1);   //选择
define('QUALITY_TYPE_FILL', 2);     //填空
define('QUALITY_TYPE_SELECT', 3);   //多选

//车辆参数
define('PARAM_TYPE_VEHICLE_TYPE', 1);

define('QUALITY_PROCESS_ITEM',1);   //质检项
define('QUALITY_PROCESS_GROUP',2);  //质检项组

//质检项类别（入厂和整车质检没有工位）
define('QUALITY_ITEM_TYPE_BEGIN',1);    //入厂
define('QUALITY_ITEM_TYPE_DURING',2);   //质检
define('QUALITY_ITEM_TYPE_OVER',3);     //整车质检

//必要角色字典
define('ROLE_DISPATCHER',  3);      //调度员
define('ROLE_IDENTIFY',    4);      //入场检定员
define('ROLE_STAFF',       5);      //员工
define('ROLE_STAFF_LEADER',6);      //员工长
define('ROLE_RESOLVE',     7);      //分解员
define('ROLE_INSPECTION',  8);      //专检
define('ROLE_ALL_INSPECTION',9);    //整车质检
define('ROLE_SUPERVISOR',  10);     //监造

