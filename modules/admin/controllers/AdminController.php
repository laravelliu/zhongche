<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;


/**
 * Default controller for the `admin` module
 */
class AdminController extends BaseController
{
    public function appendBehaviors()
    {
        return [
            'permission' => [
                'class' => PermissionFilter::className(),
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取用户列表
     * @author: liuFangShuo
     */
    public function actionGetUsers()
    {

    }

    public function actionUserList()
    {
        return $this->render('test');
    }
}
