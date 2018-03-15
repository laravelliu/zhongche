<?php

namespace app\modules\admin\controllers;

class PermissionController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetPermission()
    {
        return $this->ajaxReturn();
    }

    public function actionAddPermission()
    {
        return $this->render('add-permission',['model' => $model]);
    }

    public function actionEditPermission()
    {
        return $this->render('edit-permission',['model' => $model]);
    }

}
