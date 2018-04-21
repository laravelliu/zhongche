<?php

namespace app\models\ar;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "zc_type_work_area".
 *
 * @property int $id 质检种类所拥有的产线
 * @property int $work_area_id 物理产线id
 * @property int $type_id 质检类别id
 * @property int $is_standard 是否为标准产线
 * @property int $create_time
 * @property int $update_time
 */
class TypeWorkAreaAR extends \app\models\ar\BaseAR
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zc_type_work_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_area_id', 'type_id'], 'required'],
            [['work_area_id', 'type_id', 'is_standard', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_area_id' => 'Work Area ID',
            'type_id' => 'Type ID',
            'is_standard' => 'Is Standard',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * 批量操作
     * @param $data
     * @param $del
     * @param $type
     * @return bool|int
     * @author: liuFangShuo
     */
    public function saveBatch($data, $del,$type)
    {
        if (empty($data)  && empty($del)) {
            return true;
        }

        $connection  = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {

            //要插入数据
            if ( !empty($data) ) {
                foreach ($data as $v) {
                    $arr[] = '('.implode(',',$v).')';
                }

                $str = implode(',', $arr);

                $sql = "insert into " . TypeWorkAreaAR::tableName() . " (workshop_id, work_area_id, station_id, type_id, create_time, update_time) VALUES " . $str;


                $command = $connection->createCommand($sql);
                $status = $command->execute();

                if (!$status) {
                    throw new Exception('错误');
                }
            }

            //要删除的数据
            if (!empty($del)) {
                $res = $connection->createCommand()->delete(TypeWorkAreaAR::tableName(),'type_id='.$type.' and station_id in('.implode(',', $del).')')->execute();

                if (!$res) {
                    throw new Exception('错误');
                }
            }

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }
}
