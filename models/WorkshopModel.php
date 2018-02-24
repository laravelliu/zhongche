<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/13
 * Time: 11:17
 */

namespace app\models;


use app\models\ar\StationAR;
use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use yii\base\Model;
use yii\helpers\ArrayHelper;

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

    /**
     * 获取工位
     * @author: liuFangShuo
     */
    public function getStationList()
    {
        $stationList = StationAR::find()->where(['is_deleted' => STATUS_FALSE])->orderBy(['update_time' => SORT_ASC])->asArray()->all();
        return $stationList;
    }

    /**
     * 三级联动-获取车间列表
     * @return array
     * @author: liuFangShuo
     */
    public function getWorkshop()
    {
        $workshop = $this->getWorkshopList();
        return  ArrayHelper::map($workshop,'id','name');
    }

    /**
     * 三级联动-获取产线列表
     * @param int $wsId
     * @return array
     * @author: liuFangShuo
     */
    public function getWorkArea($wsId=0)
    {
        $sql = WorkAreaAR::find()->where(['is_deleted' => STATUS_FALSE]);

        if (!empty($wsId)) {
            $sql->where(['workshop_id' => $wsId]);
        }

        $workAreaList = $sql->asArray()->all();

        return ArrayHelper::map($workAreaList, 'id', 'name');

    }

    /**
     * 三级联动-获取工位列表
     * @param int $waId
     * @return array
     * @author: liuFangShuo
     */
    public function getStation($waId=0)
    {
        $sql = StationAR::find()->where(['is_deleted' => STATUS_FALSE]);

        if (!empty($waId)) {
            $sql->where(['work_area_id' => $waId]);
        }

        $stationList = $sql->asArray()->all();

        return [0=>'无'] + ArrayHelper::map($stationList, 'id', 'name');
    }
}