<?php

namespace app\modules\admin\controllers;

class RoleController extends BaseController
{
    /**
     * 角色管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 角色分配
     * @author: liuFangShuo
     */
    public function actionDistribution()
    {
        return $this->render('distribution');
    }
}
