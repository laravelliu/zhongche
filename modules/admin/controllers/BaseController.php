<?php

namespace app\modules\admin\controllers;

class BaseController extends \yii\web\Controller
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
