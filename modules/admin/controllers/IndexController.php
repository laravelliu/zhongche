<?php

namespace app\modules\admin\controllers;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
