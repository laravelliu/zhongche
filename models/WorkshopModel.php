<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/13
 * Time: 11:17
 */

namespace app\models;


use app\models\ar\StationAR;
use app\models\ar\StationUserGroupAR;
use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\NotFoundHttpException;

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
     * 获取工区列表
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

    public function getStationByCondition($where=[],$order=[],$offset=0,$limit=0)
    {
        $sql = StationAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray();

        if (!empty($where)) {
            $sql->andWhere($where);
        }

        if (!empty($order)) {
            $sql->addOrderBy($order);
        }

        if ($offset !== 0) {
            $sql->offset($offset);
        }

        if ($limit !== 0) {
            $sql->limit($limit);
        }

        $stationList = $sql->all();
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

    /**
     * 根据id获取职位
     * @param string $id
     * @return null|\yii\db\ActiveRecord
     * @author: liuFangShuo
     */
    public function getStationById(string $id)
    {
        $station = $this->getStationByCondition(['id' => $id]);

        if(empty($station)){
            return null;
        }

        return $station[0];
    }

    /**
     * 根据id获取车间
     * @param string $id
     * @return null|static
     * @author: liuFangShuo
     */
    public function getWorkshopById(string $id)
    {
        return WorkshopAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
    }


    /**
     * 根据id获取工区
     * @author: liuFangShuo
     */
    public function getWorkAreaById(string $id)
    {
        return WorkAreaAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
    }

    /**
     *  根据工位获取员工组
     * @param string $id
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getUserGroupByStation(string $id)
    {
       return StationUserGroupAR::find()->where(['station_id' => $id])->asArray()->all();
    }

    /**
     * 保存职位员工组
     * @author: liuFangShuo
     */
    public function saveStationGroup($id, $select = [], $delete = [])
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            $station = $this->getStationById($id);

            if(empty($station)){
                throw new NotFoundHttpException('工位不存在');
            }

            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_station_user_group where station_id=$id and user_group_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = StationUserGroupAR::find()->where(['station_id' => $id, 'user_group_id' => $select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'user_group_id');
                }

                $diff = array_diff($select, $haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(StationUserGroupAR::tableName(), ['station_id', 'user_group_id', 'create_time', 'update_time'], $arrSelect)->execute();

                    if (!$res) {
                        throw new NotFoundHttpException('新增失败');
                    }
                }

            }

            $trans->commit();
            return true;

        } catch (\Exception $e){
            $trans->rollBack();
            $this->addError('name', $e->getMessage());
            return false;
        }
    }
}