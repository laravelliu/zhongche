<?php

namespace app\modules\admin\controllers;


/**
 * Default controller for the `admin` module
 */
class AdminController extends BaseController
{
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
