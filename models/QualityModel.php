<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/26
 * Time: 09:12
 */

namespace app\models;


use app\models\ar\ProcessAR;
use app\models\ar\QualityInspectionGroupAR;
use app\models\ar\QualityInspectionItemAR;
use app\models\ar\QualityInspectionGroupItemAR;
use app\models\ar\TaskAR;
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
    public function qualityProcessList()
    {
        $qualityProcess = ProcessAR::find()->where(['is_deleted' =>STATUS_FALSE])->asArray()->all();
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
    public function getTypeArea($typeId)
    {
        $stations = TypeWorkAreaAR::find()->where(['type_id' => $typeId])->asArray()->all();
        return $stations;
    }

    /**
     * 根据质检类型获取职能工位
     * @param $type
     * @author: liuFangShuo
     */
    public function getJobStation($type)
    {

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

            //去除每个车间多余的产线
            foreach ($data as $k => $v) {
                if(count($v) == 1){
                    continue;
                }

                //拿出一个所有key
                $keys = array_keys($data[$k]);
                $chooseKey = rand(0,count($keys));

                //随机拿一个工区
                $data[$k] = [$keys[$chooseKey] =>$data[$k][$keys[$chooseKey] ]];
            }

        print_r($data);
            //查询一下职能工位
            $jobStation = $this->getJobStation($type);

            //职能工位不为空，清除职能工位的质检流程和质检项
            if (!empty($jobStation)) {
                //清除质检流程

                //清除质检项
            }

            //删除原有职能工位

            //新增职能工位


        }catch (\Exception $e){
            $trans->rollBack();
            $this->addError('name',$e->getMessage());
            return false;
        }

    }
}