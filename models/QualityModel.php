<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/26
 * Time: 09:12
 */

namespace app\models;


use app\models\ar\AnswerAR;
use app\models\ar\JobProcessAR;
use app\models\ar\JobStationAR;
use app\models\ar\JobStationItemAR;
use app\models\ar\JobStationRelateStationAR;
use app\models\ar\ProcessAR;
use app\models\ar\QualityGroupProcessAR;
use app\models\ar\QualityInspectionGroupAR;
use app\models\ar\QualityInspectionItemAR;
use app\models\ar\QualityInspectionGroupItemAR;
use app\models\ar\TaskAR;
use app\models\ar\TaskExecuteRecordAR;
use app\models\ar\TypeAR;
use app\models\ar\TypeWorkAreaAR;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\NotFoundHttpException;

class QualityModel extends Model
{
    /**
     * 获取质检项列表
     * @author: liuFangShuo
     */
    public function getQualityList()
    {
       $qualityList = QualityInspectionItemAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
       return $qualityList;
    }

    /**
     * 获取职能工位所属质检流程的其他职能工位配置的质检项
     * @param $station
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getOtherSelectItem($station)
    {
        $itemsList = JobStationAR::find()->where(['type_id' => $station->type_id, 'is_deleted' => STATUS_FALSE])->asArray()->all();
        $ids = array_column($itemsList,'id');

        //去掉本职能工位
        $otherIds = array_diff($ids, [$station->id]);

        $items = JobStationItemAR::find()->where(['job_station_id' => $otherIds])->asArray()->all();

        return $items;
    }


    /**
     * 获取质量类别
     * @author: liuFangShuo
     */
    public function getQualityType()
    {
        $qualityType = TypeAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();

        return $qualityType;
    }

    /**
     * 质检类型列表
     * @author: liuFangShuo
     */
    public function qualityTypeList()
    {
        $qualityTypeList = $this->getQualityType();
        $typeList = [0=>'无'] + ArrayHelper::map($qualityTypeList,'id', 'name');

        return $typeList;
    }

    /**
     * 质检组
     * @author: liuFangShuo
     */
    public function getQualityGroup()
    {
        $qualityList = QualityInspectionGroupAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $qualityList;
    }

    /**
     * 质检流程
     * @author: liuFangShuo
     */
    public function qualityProcessList($type = 0)
    {
        switch ($type){
            case 1:
                $where = ['is_deleted' => STATUS_FALSE, 'type' => QUALITY_PROCESS_ITEM];
                break;
            case 2:
                $where = ['is_deleted' => STATUS_FALSE, 'type' => QUALITY_PROCESS_GROUP];
                break;
            default:
                $where = ['is_deleted' =>STATUS_FALSE];
                break;
        }

        $qualityProcess = ProcessAR::find()->where($where)->asArray()->all();
        return $qualityProcess;
    }

    /**
     * 根据id获取质检项组
     * @param string $id
     * @return null|static
     * @author: liuFangShuo
     */
    public function getQualityGroupById(string $id)
    {
        return QualityInspectionGroupAR::findOne(['is_deleted' => STATUS_FALSE, 'id' => $id]);
    }

    /**
     * 根据质检类型获取质检项组
     * @author: liuFangShuo
     */
    public function getQualityGroupByTypeId($typeId)
    {
        $return = QualityInspectionGroupAR::find()->where(['type_id' => $typeId, 'is_deleted' => STATUS_FALSE])->orderBy(['item_type' => SORT_ASC])->asArray()->all();
        return $return;
    }

    /**
     * 根据质检项组获取质检项
     * @param string $groupId
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getQualityItemByGroupId(string $groupId)
    {
        $itemId = QualityInspectionGroupItemAR::find()->where(['group_id' => $groupId])->asArray()->all();
        return $itemId;
    }

    /**
     * 更改质检组的质检项
     * @author: liuFangShuo
     */
    public function saveGroupItem($id, $select = [], $delete = [])
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            $qualityGroup = $this->getQualityGroupById($id);
            if(empty($qualityGroup)){
                throw new NotFoundHttpException('质检组不存在');
            }


            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_quality_inspection_group_item where group_id=$id and item_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = QualityInspectionGroupItemAR::find()->where(['group_id' =>$id, 'item_id' =>$select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'item_id');
                }

                $diff = array_diff($select,$haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(QualityInspectionGroupItemAR::tableName(), ['group_id', 'item_id', 'create_time', 'update_time'], $arrSelect)->execute();

                    if(!$res){
                        throw new NotFoundHttpException('新增失败');
                    }
                }

            }

            $trans->commit();
            return true;

        } catch (\Exception $e){
            $trans->rollBack();
            $this->addError('name',$e->getMessage());
            return false;
        }
    }

    /**
     * 获取任务列表
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getTaskList()
    {
        return TaskAR::find()->asArray()->all();
    }

    /**
     * 根据质检类型获取所有设计物理工位
     * @param $typeId
     * @return static[]
     * @author: liuFangShuo
     */
    public function getTypeArea($typeId,$condition = [])
    {
        if(empty($condition)){
            $where = ['type_id' => $typeId];
        } else {
            $where['type_id'] = $typeId;
            $where = array_merge($where,$condition);
        }

        $stations = TypeWorkAreaAR::find()->where($where)->asArray()->all();
        return $stations;
    }


    /**
     * 根据质检类型获取职能工位
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getJobStation($type)
    {
        if ($type == 0) {
            $where = ['is_deleted' => STATUS_FALSE];

        } else {
            $where = ['type_id' => $type, 'is_deleted' => STATUS_FALSE];
        }

        $typeWorkArea = JobStationAR::find()->where($where)->asArray()->all();
        return $typeWorkArea;
    }

    /**
     *  重新生成职能工位
     * @author: liuFangShuo
     */
    public function changeJobQuality($type)
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            //查询一下物理工位
            $stations = $this->getTypeArea($type);
            if (empty($stations)) {
                throw new Exception('请给质检类型分配物理工位');
            }

            //每个车间选出一条产线
            foreach ($stations as $st) {
                $data[$st['workshop_id']][$st['work_area_id']][] = $st['station_id'];
            }

            //每个车间工位数量
            $workshopNum = [];
            //去除每个车间多余的产线
            foreach ($data as $k => $v) {

                foreach ($v as $m) {
                    $workshopNum[$k] = count($m);
                }
            }

            //查询一下职能工位
            $jobStation = $this->getJobStation($type);
            //职能工位不为空，清除职能工位的质检流程和质检项
            if (!empty($jobStation)) {
                $jobstations = array_column($jobStation,'id');

                //清除质检流程
                JobProcessAR::deleteAll(['job_station_id' => $jobstations]);

                //清除质检项
                JobStationItemAR::deleteAll(['job_station_id' => $jobstations]);

                //清除职能工位和物理工位的关系
                JobStationRelateStationAR::deleteAll(['job_station_id' => $jobstations]);

                //删除原有职能工位
                JobStationAR::updateAll(['is_deleted' => STATUS_TRUE],['id' => $jobstations]);
            }

            $arr = [];
            $time = time();
            foreach ($workshopNum as $k => $v) {
                for($i=1; $i<=$v; $i++) {
                    $arr[] = "($type,$k,$time,$time)";
                }
            }

            $str = implode(',',$arr);
            //新增职能工位
            $sql = 'insert into zc_job_station (type_id, workshop_id,create_time,update_time) values '.$str;
            $res= \Yii::$app->db->createCommand($sql)->query();

            if(!$res){
                throw new NotFoundHttpException('新增失败');
            }

            $trans->commit();
            return true;

        }catch (\Exception $e){
            $trans->rollBack();
            $this->addError('name',$e->getMessage());
            return false;
        }

    }

    /**
     * 根据车间获取职能工位
     * @param $type
     * @param $workshop
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getJobStationByWorkshop($type,$workshop)
    {
        $stations = JobStationAR::find()->where(['type_id' => $type, 'workshop_id' => $workshop, 'is_deleted' => STATUS_FALSE])->asArray()->all();
        return $stations;
    }

    /**
     * 根据id获取职能工位
     * @param $id
     * @return null|static
     * @author: liuFangShuo
     */
    public function getJobStationById($id)
    {

        return JobStationAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);
    }

    /**
     * 获取职能工位对应的质检流程
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getProcessByJob($id)
    {
        $Station = JobProcessAR::find()->where(['job_station_id' => $id])->asArray()->all();
        return $Station;
    }

    public function saveJobProcess($id, $select = [], $delete = []){
        $trans = Yii::$app->db->beginTransaction();

        try{
            $station = $this->getJobStationById($id);

            if (empty($station)) {
                throw new NotFoundHttpException('职能工位不存在');
            }

            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_job_process where job_station_id=$id and process_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = JobProcessAR::find()->where(['job_station_id' => $id, 'process_id' => $select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'process_id');
                }

                $diff = array_diff($select, $haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(JobProcessAR::tableName(), ['job_station_id', 'process_id', 'create_time', 'update_time'], $arrSelect)->execute();

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

    /**
     * 根据职能工位获取质检项
     * @author: liuFangShuo
     */
    public function getItemByJob($id)
    {
        $items = JobStationItemAR::find()->where(['job_station_id' => $id])->asArray()->all();
        return $items;
    }


    public function getOtherItemByJob()
    {

    }


    /**
     * 保存配置的质检项
     * @author: liuFangShuo
     */
    public function saveJobItem($id, $select = [], $delete = [])
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            $station = $this->getJobStationById($id);

            if (empty($station)) {
                throw new NotFoundHttpException('职能工位不存在');
            }

            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_job_station_item where job_station_id=$id and item_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = JobStationItemAR::find()->where(['job_station_id' => $id, 'item_id' => $select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'item_id');
                }

                $diff = array_diff($select, $haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(JobStationItemAR::tableName(), ['job_station_id', 'item_id', 'create_time', 'update_time'], $arrSelect)->execute();

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


    /**
     * 保存职能工位和物理工位的关系
     * @param $jobStation
     * @param $stationList
     * @return int
     * @author: liuFangShuo
     */
    public function saveJobStation($jobStation,$stationList)
    {
        JobStationRelateStationAR::deleteAll(['job_station_id' => $jobStation->id]);

        //组织数据
        $data = [];
        foreach ($stationList as $k => $v){
            $data[] = [$jobStation->id,$jobStation->type_id,$v['workshop_id'],$v['work_area_id'],$v['station_id'],time(),time()];
        }

        $res = \Yii::$app->db->createCommand()->batchInsert(JobStationRelateStationAR::tableName(), ['job_station_id', 'type_id','workshop_id','work_area_id','station_id', 'create_time', 'update_time'], $data)->execute();

        return $res;
    }

    /**
     * 根据质检项组获取质检流程
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getProcessByGroup($id)
    {
        $Station = QualityGroupProcessAR::find()->where(['quality_group_id' => $id])->asArray()->all();
        return $Station;
    }


    public function saveGroupProcess($id, $select = [], $delete = [])
    {
        $trans = Yii::$app->db->beginTransaction();

        try{
            $group = $this->getQualityGroupById($id);

            if (empty($group)) {
                throw new NotFoundHttpException('质检项组不存在');
            }

            if (!empty($delete)) {
                $arrDel = [];
                foreach ($delete as $k => $v){
                    $arrDel[] = [$id,$v];
                }

                $sql = "delete from zc_quality_group_process where quality_group_id=$id and process_id in (" .implode(',',$delete).")";

                $res= \Yii::$app->db->createCommand($sql)->query();

                if(!$res){
                    throw new NotFoundHttpException('删除失败');
                }
            }

            if (!empty($select)) {

                $haveSelect = QualityGroupProcessAR::find()->where(['quality_group_id' => $id, 'process_id' => $select])->asArray()->all();

                if (!empty($haveSelect)) {
                    $haveSelect = array_column($haveSelect,'process_id');
                }

                $diff = array_diff($select, $haveSelect);


                if (!empty($diff)) {
                    $arrSelect = [];
                    foreach ($diff as $k => $v){
                        $arrSelect[] = [$id,$v,time(),time()];
                    }

                    $res= \Yii::$app->db->createCommand()->batchInsert(QualityGroupProcessAR::tableName(), ['quality_group_id', 'process_id', 'create_time', 'update_time'], $arrSelect)->execute();

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

    /**
     * 获取在同一质检流程中不同质检项组已分配的质检项
     * @param $obj
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function getOtherGroupSelectItem($obj)
    {
        $groupList = QualityInspectionGroupAR::find()->where(['type_id' => $obj->type_id, 'is_deleted' => STATUS_FALSE])->asArray()->all();
        $ids = array_column($groupList,'id');

        //去掉本职能工位
        $otherIds = array_diff($ids, [$obj->id]);

        $items = QualityInspectionGroupItemAR::find()->where(['group_id' => $otherIds])->asArray()->all();

        return $items;
    }

    /**
     * 根据ID数组获取质检项
     * @author: liuFangShuo
     */
    public function getQualityItemByIds($ids)
    {
        $items = QualityInspectionItemAR::find()->where(['is_deleted' => STATUS_FALSE, 'id' => $ids])->asArray()->all();

        return $items;
    }

    /**
     * 获取答案
     * @author: liuFangShuo
     */
    public  function getAnswer($taskId,$itemArr)
    {
        $answerList = AnswerAR::find()->select('zc_answer.*,zc_quality_inspection_item.title as title,zc_quality_inspection_item.standard as standard')->join('LEFT JOIN','zc_quality_inspection_item','zc_answer.quality_item_id = zc_quality_inspection_item.id')->where(['zc_answer.task_id' => $taskId, 'zc_answer.quality_item_id' => $itemArr])->asArray()->all();
        return $answerList;
    }

    /**
     * 获取执行结果
     * @param $taskId
     * @param $groupId
     * @author: liuFangShuo
     */
    public function getTaskExeRecord($taskId,$groupId)
    {
        $exeRecord = TaskExecuteRecordAR::find()->select('zc_task_execute_record.*,zc_user.name as doname')->join('LEFT JOIN','zc_user','zc_task_execute_record.user_id = zc_user.id')->where(['zc_task_execute_record.task_id'=>$taskId, 'zc_task_execute_record.quality_inspection_group_id' => $groupId])->asArray()->all();
        return $exeRecord;
    }
}