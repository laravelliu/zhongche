<?php

/**
 * 配置
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/15
 * Time: 09:02
 */

return [
    'adminEmail' => 'admin@example.com',
    'errorEmail' => ['xinguozhong@pdmi.cn', 'liufangshuo@pdmi.cn', 'xuliyuan@pdmi.cn'],
    'quality_type' => [QUALITY_TYPE_JUDGE => '判断类型', QUALITY_TYPE_CHOOSE => '选择类型', QUALITY_TYPE_FILL => '填空类型', QUALITY_TYPE_SELECT => '多选类型', QUALITY_TYPE_COMB => '混合题类型（先判断再填空）'],
    'param_type' => [PARAM_TYPE_VEHICLE_TYPE => '车辆类别参数'],
    'quality_process' => [QUALITY_PROCESS_ITEM => '质检项',QUALITY_PROCESS_GROUP => '质检项组'],
    'quality_item_type' => [QUALITY_ITEM_TYPE_BEGIN => '入厂鉴定',QUALITY_ITEM_TYPE_DURING=>'有工位质检',QUALITY_ITEM_TYPE_OVER=>'整车质检'],
    'quality_group_need_decomposition' => [STATUS_FALSE => '否', STATUS_TRUE => '是']
];
