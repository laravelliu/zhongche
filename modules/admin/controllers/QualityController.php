<?php

namespace app\modules\admin\controllers;

use app\models\ar\JobStationAR;
use app\models\ar\ProcessAR;
use app\models\ar\QualityInspectionGroupAR;
use app\models\ar\QualityInspectionItemAR;
use app\models\ar\TypeAR;
use app\models\ar\TypeWorkAreaAR;
use app\models\ar\WorkshopAR;
use app\models\QualityModel;
use app\models\WorkshopModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class QualityController extends BaseController
{
    /**
     * 质检列表
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 添加质检项
     * @author: liuFangShuo
     */
    public function actionAddQualityItem()
    {
        $model = new QualityInspectionItemAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                $model->standard = Html::encode($model->standard);
                if ($model->saveQuality()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/index']));
                }
            }

            $model->getErrors();

        }

        //默认
        return $this->render('add-quality',['model' => $model]);
    }

    /**
     * 获取质检项
     * @author: liuFangShuo
     */
    public function actionGetQualityItem()
    {
        $model = new QualityModel();
        $qualityItems = $model->getQualityList();

        if(!empty($qualityItems)){
            foreach ($qualityItems as $k => $item){
                $qualityItems[$k]['standard'] =json_decode($item['standard'],true);
                $qualityItems[$k]['type'] = Yii::$app->params['quality_type'][$item['type']];
                $qualityItems[$k]['create_time'] = date('Y-m-d H:i:s', $item['create_time']);
                $qualityItems[$k]['update_time'] = date('Y-m-d H:i:s', $item['update_time']);
            }
        }
        return $this->ajaxReturn($qualityItems);
    }

    /**
     * 编辑质检项
     * @author: liuFangShuo
     */
    public function actionEditQualityItem()
    {
        $id =  Yii::$app->request->get('id', null);

        if(empty($id)){
            return $this->redirect(Url::to(['quality/index']));
        }
        $model = QualityInspectionItemAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if (empty($model)) {
            $data = [
                'title' => '质检项不存在',
                'content' => '没有此质检项信息，请返回质检项列表确认。',
                'button' => '返回质检项列表',
                'url' => Url::to(['quality/index'])
            ];
            return $this->render('/workshop/empty',['data' => $data]);
        }

        $model->setScenario('update');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                $model->standard = Html::encode($model->standard);
                if ($model->saveQuality()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/index']));
                }
            }

            $model->getErrors();

        }

        //反解json
        if (!empty( $model->standard)) {
            $model->standard = json_decode($model->standard,true);
            $model->standard = implode(';',$model->standard );
        }

        //默认
        return $this->render('edit-quality',['model' => $model]);
    }

    /**
     * 质检类别
     * @author: liuFangShuo
     */
    public function actionQualityType()
    {
        return $this->render('type');
    }

    /**
     * 质检类别列表
     * @author: liuFangShuo
     */
    public function actionQualityTypeList()
    {
        $model = new QualityModel();
        $qualityList = $model->getQualityType();

        if(!empty($qualityList)){

            foreach ($qualityList as $k =>$v){
                $qualityList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $qualityList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($qualityList);
    }

    /**
     * 增加质检类别
     * @author: liuFangShuo
     */
    public function actionAddQualityType()
    {
        $model = new TypeAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveType()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/quality-type']));
                }
            }

            $model->getErrors();

        }

        return $this->render('add-type',['model' => $model]);
    }

    /**
     * 编辑质检类别
     * @author: liuFangShuo
     */
    public function actionEditQualityType()
    {
        $id = Yii::$app->request->get('id', null);
        $model = TypeAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['quality/quality-type']));
        }

        $model->setScenario('update');

        if(Yii::$app->request->isPost){

            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveType()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/quality-type']));
                }
            }

            $model->getErrors();

        }

        return $this->render('edit-type',['model' => $model]);
    }

    /**
     * 质检项组
     * @author: liuFangShuo
     */
    public function actionQualityGroup()
    {
        //检查是否有质检类别
        $model = new QualityModel();
        $qualityType = $model->getQualityType();

        if(empty($qualityType)){
            $data = [
                'title' => '缺少质检类型',
                'content' => '没有质检类型，质检项组需要质检类型信息作为前置条件，请添加质检类型。',
                'button' => '添加质检类型',
                'url' => Url::to(['quality/add-quality-type'])
            ];
            return $this->render('/workshop/empty', ['data' => $data]);

        }
        return $this->render('quality-group');
    }

    /**
     * 获取质检项组
     * @author: liuFangShuo
     */
    public function actionGetQualityGroup()
    {
        $model = new QualityModel();
        $qualityGroupList = $model->getQualityGroup();
        $qyalityType = $model->getQualityType();
        $qyalityTypeList = ArrayHelper::map($qyalityType,'id','name');


        if(!empty($qualityGroupList)){
            foreach ($qualityGroupList as $k => $v){
                $qualityGroupList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $qualityGroupList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $qualityGroupList[$k]['type'] = $qyalityTypeList[$v['type_id']];
                $qualityGroupList[$k]['item_type'] = Yii::$app->params['quality_item_type'][$v['item_type']];
            }
        }
        return $this->ajaxReturn($qualityGroupList);
    }

    /**
     * 编辑质检项组
     * @return string
     * @author: liuFangShuo
     */
    public function actionEditQualityGroup()
    {
        $id = Yii::$app->request->get('id', null);

        $model = QualityInspectionGroupAR::findOne(['id' => $id,'is_deleted'=>STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(['quality/quality-group']);
        }
        $model->setScenario('update');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveGroup()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/quality-group']));
                }
            }

            $model->getErrors();
        }

        $qualutyModel = new QualityModel();
        $qualityType = $qualutyModel->getQualityType();
        $qualityType = ArrayHelper::map($qualityType,'id','name');

        return $this->render('edit-quality-group', ['model' => $model, 'qualityType' => $qualityType]);
    }

    /**
     * 添加质检项组
     * @return string
     * @author: liuFangShuo
     */
    public function actionAddQualityGroup()
    {
        $model = new QualityInspectionGroupAR();
        $model->setScenario('create');

        $qualutyModel = new QualityModel();
        $qualityType = $qualutyModel->getQualityType();
        $qualityType = ArrayHelper::map($qualityType,'id','name');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveGroup()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/quality-group']));
                }
            }

            $model->getErrors();
        }

        return $this->render('add-quality-group',['model' => $model, 'qualityType' => $qualityType]);

    }

    /**
     * 质检流程
     * @author: liuFangShuo
     */
    public function actionQualityProcess()
    {
        return $this->render('process');
    }

    /**
     * 获取质检流程
     * @author: liuFangShuo
     */
    public function actionGetQualityProcess()
    {
        $model = new QualityModel();
        $process = $model->qualityProcessList();

        if(!empty($process)){
            foreach ($process as $k => $v){
                $process[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $process[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $process[$k]['type'] = Yii::$app->params['quality_process'][$v['type']];
            }
        }

        return $this->ajaxReturn($process);
    }

    /**
     * 添加质检流程
     * @author: liuFangShuo
     */
    public function actionAddQualityProcess()
    {
        $model = new ProcessAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveProcess()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/quality-process']));
                }
            }

            $model->getErrors();
        }

        return $this->render('add-quality-process',['model' => $model]);

    }

    /**
     * 编辑质检流程
     * @author: liuFangShuo
     */
    public function actionEditQualityProcess()
    {
        $id = Yii::$app->request->get('id', null);

        $model = ProcessAR::findOne(['id' => $id,'is_deleted'=>STATUS_FALSE]);

        if(empty($id) || empty($model)){
            return $this->redirect(['quality/quality-process']);
        }
        $model->setScenario('update');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model->validate()){
                if ($model->saveProcess()) {
                    //成功跳转
                    return $this->redirect(Url::to(['quality/quality-process']));
                }
            }

            $model->getErrors();
        }

        return $this->render('edit-quality-process',['model'=>$model]);
    }

    /**
     *  质检任务
     * @author: liuFangShuo
     */
    public function actionTask()
    {
        return $this->render('task');
    }


    /**
     *
     * @author: liuFangShuo
     */
    public function actionGetTaskList()
    {
        $model = new QualityModel();
        $taskList = $model->getTaskList();

        //查询车辆信息

        //查询质检类型

        //查询
        if (!empty($taskList)) {
            foreach ($taskList as $k => $v) {
                $taskList[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                $taskList[$k]['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
            }
        }

        return $this->ajaxReturn($taskList);
    }

    /**
     * 给质检项组分配质检项
     * @author: liuFangShuo
     */
    public function actionAddItem()
    {
        $id = Yii::$app->request->get('id', null);

        //获取质检项组
        $model = new QualityModel();
        $qualityGroup = $model->getQualityGroupById($id);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['quality/quality-group']));
        }

        //获取所有质检项
        $qualityListAll = $model->getQualityList();
        $all = ArrayHelper::map($qualityListAll,'id','title');

        $selected = [];
        $unSelect = [];
        $selectedItem = $model->getQualityItemByGroupId($id);

        if (empty($selectedItem)) {

            $unSelect = $all;
        } else {

            //已经选择的类型
            foreach ($selectedItem as $k => $v) {
                $selected[$v['item_id']] = $all[$v['item_id']];
            }

            $unSelect = array_diff($all,$selected);
        }

        return $this->render('add-item', ['group' => $qualityGroup, 'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect,'id'=>$id,'url'=>Url::to(['quality/save-item'])]]);
    }

    /**
     * 质检项组保存质检项
     * @author: liuFangShuo
     */
    public function actionSaveItem()
    {
        if (Yii::$app->request->isAjax) {
            $select = Yii::$app->request->post('selected',null);
            $unSelect = Yii::$app->request->post('unSelect',null);
            $id = Yii::$app->request->post('id', null);

            if (empty($id)) {
                return $this->ajaxReturn([],1,'不存在id');
            }

            $selectId = [];
            $unSelectId = [];

            if (!empty($select)) {
                foreach ($select as $k => $v) {
                    $selectId[] =  $v['0'];
                }
            }

            if (!empty($unSelect)) {
                foreach ($unSelect as $k => $v) {
                    $unSelectId[] =  $v['0'];
                }
            }


            $model = new QualityModel();
            $res = $model->saveGroupItem($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1,$model->getFirstError('name'));
        }

        return false;
    }

    /**
     * 选择能做此质检类型的工区
     * @author: liuFangShuo
     */
    public function actionDistributionArea()
    {
        //获取质检类型
        $id = Yii::$app->request->get('id',null);
        $type = TypeAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        //获取质检type对应的物理工位
        $typeStationModel = new QualityModel();
        $chooseStations = $typeStationModel->getTypeArea($type);

        //
        $chooseArr = [];
        foreach ($chooseStations as $k => $v) {
            $chooseArr[$v['workshop_id']][$v['work_area_id']][] = $v['station_id'];
        }

        //获取车间
        $model = new WorkshopModel();
        $workshopList = $model->getWorkshopList();

        //获取车间
        $workArea = $model->getWorkAreaList();

        //获取工位
        $station = $model->getStationByCondition([],['work_area_id' => SORT_ASC]);

        $stationArr=[];
        if (!empty($station)) {
            foreach ($station as $sk => $sv){
                $sv['status'] = 0;

                if (isset($chooseArr[$sv['workshop_id']][$sv['work_area_id']]) && in_array( $sv['id'], $chooseArr[$sv['workshop_id']][$sv['work_area_id']])) {
                    $sv['status'] = 1;
                }

                $stationArr[$sv['work_area_id']][] = $sv;
            }
        }

        $workAreaArr=[];
        if (!empty($workArea)) {
            foreach ($workArea as $wak => $wav) {
                $wav['status'] = 0;

                if(isset($chooseArr[$wav['workshop_id']][$wav['id']])){
                    $wav['status'] = 1;
                }

                $wav['station'] = isset($stationArr[$wav['id']]) ? $stationArr[$wav['id']]:[];
                $workAreaArr[$wav['workshop_id']][] = $wav;
            }
        }

        $data=[];
        if (!empty($workshopList)) {
            foreach ($workshopList as $wsk => $wsv) {
                $wsv['workArea'] = isset($workAreaArr[$wsv['id']])?$workAreaArr[$wsv['id']]:[];
                $data[$wsv['id']] = $wsv;
            }
        }

        //数据不全
        if(empty($data)){
            $data = [
                'title' => '缺少厂房基础数据',
                'content' => '没有厂房基础数据，质检项组选择产线需要厂房基础数据作为前置条件，请添加厂房基础数据。',
                'button' => '车间信息',
                'url' => Url::to(['workshop/workshop'])
            ];
            return $this->render('/workshop/empty', ['data' => $data]);

        }

        $workshopJs = array_column($workshopList,'id');
        //print_r($data);exit;
        return $this->render('distribution',['data' => $data,'type' => $type, 'workshopJs' => $workshopJs, 'chooseStation' => $chooseArr]);
    }

    /**
     * 保存信息
     * @return object
     * @author: liuFangShuo
     */
    public function actionPostTypeWorkArea()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $type = $data['type'];
            $data = $data['data'];
            $cao = [];

            //获取已经选择
            $model = new QualityModel();
            $choose = $model->getTypeArea($type);

            //更改数据
            $getStaArr = [];
            $chooseStaArr = [];
            $insertStaArr = [];
            $deleStaArr = [];

            if (!empty($choose)) {
                $chooseStaArr = array_column($choose,'station_id');
            }

            //组装数据
            foreach ($data as $k => $v) {
                $workshop = $v['workshop'];

                foreach ($v['value'] as $a => $b) {
                    $workArea = $b['workarea'];

                    foreach ($b['value'] as $st) {
                        $getStaArr[] = $st;

                        if (!empty($chooseStaArr) && in_array($st,$chooseStaArr)) {
                                continue;
                        } else {
                            $insertStaArr[] = $st;
                            $cao[]=[$workshop, $workArea, $st, $type, time(), time()];
                        }

                    }
                }
            }

            //要删除的数组
            if(!empty($chooseStaArr)){
                foreach ($chooseStaArr as $k => $v) {
                    if (!in_array($v, $getStaArr)) {
                        $deleStaArr[] = $v;
                    }
                }
            }


            $model = new TypeWorkAreaAR();
            $res = $model->saveBatch($cao, $deleStaArr, $type);

            if ($res) {
                return $this->ajaxReturn('',0,'添加成功');
            }

            return $this->ajaxReturn('',1,'添加失败');

        }

    }

    /**
     * 自动生成职能工位
     * @author: liuFangShuo
     */
    public function actionDoJobStation()
    {
        if (Yii::$app->request->isAjax) {
            $type = Yii::$app->request->post('type',0);

            if ($type == 0) {
                return $this->ajaxReturn('',1,'请传输质检类型');
            }

            $model = new QualityModel();
            $res = $model->changeJobQuality($type);

            if ($res) {
               return $this->ajaxReturn('',0);
            } else {
                return $this->ajaxReturn('',1,$model->getErrors());
            }
        }

        return false;

    }

    /**
     * 职能工位
     * @author: liuFangShuo
     */
    public function actionJobStation()
    {
        return $this->render('job-station');
    }

    /**
     * 获取职能工位列表
     * @return array|\yii\db\ActiveRecord[]
     * @author: liuFangShuo
     */
    public function actionGetJobStation()
    {
        $model = new QualityModel();
        $workModel = new WorkshopModel();
        $jobStation = $model->getJobStation(0);

        $jobArr = ArrayHelper::map($jobStation,'id','name');

        //获取质检类型
        $typeListArr = $model->getQualityType();
        $typeList = ArrayHelper::map($typeListArr,'id','name');

        //获取车间列表
        $workshopListArr = $workModel->getWorkshopList();
        $workshopList = ArrayHelper::map($workshopListArr,'id','name');


        if (!empty($jobStation)) {
            foreach ($jobStation as $k => $v){
                $jobStation[$k]['name'] = empty($v['name']) ? '无' : $v['name'];
                $jobStation[$k]['type'] = isset($typeList[$v['type_id']])?$typeList[$v['type_id']]:'错误数据';
                $jobStation[$k]['workshop'] = isset($workshopList[$v['workshop_id']])?$workshopList[$v['workshop_id']]:'错误数据';
                $jobStation[$k]['pName'] = isset($jobArr[$v['pid']]) ? $jobArr[$v['pid']]: '无';
                $jobStation[$k]['sName'] = isset($jobArr[$v['sid']]) ? $jobArr[$v['sid']]: '无';
                $jobStation[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $jobStation[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($jobStation);
    }

    /**
     * 编辑职能工位
     * @author: liuFangShuo
     */
    public function actionEditJobStation()
    {
        $id = Yii::$app->request->get('id');
        $model = JobStationAR::findOne(['id' => $id, 'is_deleted' => STATUS_FALSE]);

        //获取同一车间的职能工位
        $qualitModel = new QualityModel();
        $stations = $qualitModel->getJobStationByWorkshop($model->type_id,$model->workshop_id);

        //过滤没有名字的
        $stArr = [];
        foreach ($stations as $v) {
            if(empty($v['name']) || $v['id'] == $id){
                continue;
            }

            $stArr[$v['id']] = $v['name'];
        }

        $stationArr = [0 => '无'] + $stArr;

        //获取车间
        $workshop = WorkshopAR::findOne(['id' => $model->workshop_id, 'is_deleted' => STATUS_FALSE]);
        $workshopName = $workshop->name;

        if (Yii::$app->request->isPost) {
            if ($model->load($post = Yii::$app->request->post()) && $model->validate()) {
                if ($model->save(false)) {

                    if ($model->pid == 0) {
                        return $this->redirect(Url::to(['quality/job-station']));
                    } else {
                        $pModel = JobStationAR::findOne(['id' => $model->pid, 'is_deleted' => STATUS_FALSE]);
                        $pModel->sid = $model->id;

                        if($pModel->save(false)){
                            return $this->redirect(Url::to(['quality/job-station']));
                        } else {
                            $model->addError('name', '网络错误，请重试');
                        }
                    }

                }
            }

            $model->getErrors();
        }

        return $this->render('edit-job-station',['model' => $model, 'station' => $stationArr, 'workshop' => $workshopName]);
    }

}

