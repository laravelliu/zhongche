<?php

namespace app\modules\admin\controllers;

use app\models\WorkshopModel;

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
     * 获取车间列表
     * @author: liuFangShuo
     */
    public function actionGetWorkshop()
    {
        $model = new WorkshopModel();
        $workshopListInfo = $model->getWorkshopList();
        $workshopList = [];

        foreach ($workshopListInfo as $workshop){
            $workshop['create_time'] = date('Y-m-d H:i:s', $workshop['create_time']);
            $workshop['update_time'] = date('Y-m-d H:i:s', $workshop['update_time']);
            $workshopList[] = $workshop;
        }

        return $this->ajaxReturn($workshopList);
    }

    /**
     * 添加车间
     * @author: liuFangShuo
     */
    public function actionAddWorkshop()
    {

        return $this->render('add-workshop');
    }

    /**
     * 编辑车间
     * @author: liuFangShuo
     */
    public function actionEditWorkshop()
    {
        return $this->render('edit-workshop');

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