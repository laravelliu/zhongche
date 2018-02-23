<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/13
 * Time: 11:17
 */

namespace app\models;


use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use yii\base\Model;

class WorkshopModel extends Model
{
    /**
     * 获取车间列表
     * @author: liuFangShuo
     */
    public function getWorkshopList()
    {
        $workshopList = WorkshopAR::find()->where(['is_deleted' => STATUS_FALSE])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        return $workshopList;
    }

    /**
     * 获取产线列表
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getWorkAreaList()
    {
        $workAreaList = WorkAreaAR::find()->where(['is_deleted' => STATUS_FALSE])->orderBy(['update_time' => SORT_ASC])->asArray()->all();
        return $workAreaList;
    }

}