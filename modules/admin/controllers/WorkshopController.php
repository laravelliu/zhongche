<?php

namespace app\modules\admin\controllers;

class WorkshopController extends BaseController
{
    /**
     * 车间管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionWorkshop()
    {
        return $this->render('workshop');
    }

    /**
     * 产线管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionWorkArea()
    {
        return $this->render('area');
    }

    /**
     * 工位管理
     * @author: liuFangShuo
     */
    public function actionStation()
    {
        return $this->render('station');
    }
}