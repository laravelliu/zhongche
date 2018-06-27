<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;

class IndexController extends BaseController
{
    public function appendBehaviors()
    {
        return [
            'permission' => [
                'class' => PermissionFilter::className(),
                'except' => ['index'],
                'failUrl' => '/login'
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
