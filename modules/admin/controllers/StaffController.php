<?php

namespace app\modules\admin\controllers;

class StaffController extends BaseController
{
    /**
     * 员工信息
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 员工组管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionStaffGroup()
    {
        return $this->render('group');
    }

}
