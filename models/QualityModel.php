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
use app\models\ar\TypeAR;
use yii\base\Model;
use yii\helpers\ArrayHelper;

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
}