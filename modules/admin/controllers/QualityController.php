<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\common\helpers\WebHelper;
use app\models\ar\JobStationAR;
use app\models\ar\JobStationRelateStationAR;
use app\models\ar\ProcessAR;
use app\models\ar\QualityInspectionGroupAR;
use app\models\ar\QualityInspectionItemAR;
use app\models\ar\StationAR;
use app\models\ar\TaskAR;
use app\models\ar\TypeAR;
use app\models\ar\TypeWorkAreaAR;
use app\models\ar\WorkAreaAR;
use app\models\ar\WorkshopAR;
use app\models\QualityModel;
use app\models\VehicleModel;
use app\models\WorkshopModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class QualityController extends BaseController
{
    public function appendBehaviors()
    {
        return [
            'permission' => [
                'class' => PermissionFilter::className(),
                'failUrl' => '/login'
            ],
        ];
    }

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
        $process = $model->qualityProcessList(0);

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

        //查询
        if (!empty($taskList)) {

            //查询车辆信息
            $vehicleModel = new VehicleModel();
            $vehicleArr = array_column($taskList, 'vehicle_id');
            $vehicleInfoArr = $vehicleModel->getVehicleById($vehicleArr);
            $vehicleInfo = WebHelper::arrayChangeKey($vehicleInfoArr, 'id');

            //查询质检类型
            $qualityModel = new QualityModel();
            $type = $qualityModel->getQualityType();
            $typeList = ArrayHelper::map($type,'id', 'name');

            foreach ($taskList as $k => $v) {
                $taskList[$k]['type'] = $typeList[$v['type_id']];
                $taskList[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                $taskList[$k]['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
                $taskList[$k]['vehicle_info'] = "车辆牌照：{$vehicleInfo[$v['vehicle_id']]['plate']}<br>自重：{$v['vehicle_weight']}吨<br>载重：{$v['vehicle_full_weight']}吨";
            }


        }

        return $this->ajaxReturn($taskList);
    }

    /**
     * @author: liuFangShuo
     */
    public function actionTaskInfo()
    {
        $taskId = Yii::$app->request->get('id');
        //根据task 获取质检项组
        $taskInfo = TaskAR::findOne(['id' => $taskId/*, 'finish' => STATUS_TRUE*/]);

        if(empty($taskInfo)){
            return $this->redirect(Url::to(['quality/task']));
        }

        $model = new QualityModel();
        $itemGroupList = $model->getQualityGroupByTypeId($taskInfo->type_id);


        return $this->render('task-info',['group' => $itemGroupList, 'task' => $taskInfo]);
    }

    /**
     * 质检项组分配质检项
     * @author: liuFangShuo
     */
    public function actionAddItem()
    {
        $id = Yii::$app->request->get('id', null);

        //获取质检项组
        $model = new QualityModel();
        $qualityGroup = $model->getQualityGroupById($id);

        if(empty($id) || empty($qualityGroup)){
            return $this->redirect(Url::to(['quality/quality-group']));
        }

        //获取所有质检项
        $qualityListAll = $model->getQualityList();

        //获取当前质检流程已分配的质检项
        $otherSelect = $model->getOtherGroupSelectItem($qualityGroup);

        $all = [];
        if (!empty($otherSelect)) {
            $otherItemIds = array_column($otherSelect, 'item_id');

            foreach ($qualityListAll as $k => $v){

                if (in_array($v['id'],$otherItemIds)) {
                    continue;
                }

                $all[$v['id']] = $v['title'];
            }
        } else {
            $all = ArrayHelper::map($qualityListAll,'id','title');
        }


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
        $workshopList = WebHelper::arraySortBySid(WebHelper::arrayChangeKey($workshopList,'id'));

        //获取工区
        $workArea = $model->getWorkAreaList();

        //获取工位
        $station = $model->getStationByCondition([],['work_area_id' => SORT_ASC,'pid' => SORT_ASC]);

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

    /**
     * 分配质检流程
     * @author: liuFangShuo
     */
    public function actionDistributionProcess()
    {
        $id = Yii::$app->request->get('id',null);

        $model = new QualityModel();
        $jobStation = $model->getJobStationById($id);

        //分配质检流程
        if (empty($id)|| empty($jobStation)) {
            $this->redirect(Url::to(['quality/job-station']));
        }

        //获取质检流程
        $processList = $model->qualityProcessList(QUALITY_PROCESS_ITEM);
        $all = ArrayHelper::map($processList,'id','name');

        //获取已选择的质检流程
        $selectedProcess = $model->getProcessByJob($id);

        $selected = [];
        $unSelect = [];

        if(empty($selectedProcess)){
            $unSelect = $all;
        } else {
            //已经选择的类型
            foreach ($selectedProcess as $k => $v) {
                $selected[$v['process_id']] = $all[$v['process_id']];
            }

            $unSelect = array_diff($all,$selected);

        }


        return $this->render('distribution-process',['station' => $jobStation, 'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect, 'id'=>$id, 'url'=>Url::to(['quality/save-job-process'])]]);

    }

    /**
     * 编辑质检流程
     * @author: liuFangShuo
     */
    public function actionSaveJobProcess()
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
                    $selectId[] =  $v[0];
                }
            }

            if (!empty($unSelect)) {
                foreach ($unSelect as $k => $v) {
                    $unSelectId[] =  $v[0];
                }
            }

            $model = new QualityModel();
            $res = $model->saveJobProcess($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1, $model->getFirstError('name'));

        }

        return false;

    }

    /**
     * 分配质检项
     * @author: liuFangShuo
     */
    public function actionDistributionItem()
    {
        $id = Yii::$app->request->get('id',null);

        $model = new QualityModel();
        $jobStation = $model->getJobStationById($id);
        //分配质检流程
        if (empty($id)|| empty($jobStation)) {
            $this->redirect(Url::to(['quality/job-station']));
        }

        //获取质检项
        $itemList = $model->getQualityList();

        //获取质检项组
        $group = $model->getQualityGroupByTypeId($jobStation->type_id);
        $groupList = [0 => '全部质检项组'] + ArrayHelper::map($group, 'id', 'name');

        //获取同一种质检流程 其他职能工位已分配的问题
        //$otherItem = $model->getOtherSelectItem($jobStation);

        /*$all = [];
        if (!empty($otherItem)) {
            $otherItemIds = array_column($otherItem, 'item_id');

            foreach ($itemList as $k => $v){

                if (in_array($v['id'],$otherItemIds)) {
                    continue;
                }

                $all[$v['id']] = $v['title'];
            }
        } else {*/
            $all = ArrayHelper::map($itemList,'id','title');
        /*}*/

        //获取已选择的质检流程
        $selectedItems = $model->getItemByJob($id);

        $selected = [];
        $unSelect = [];

        if(empty($selectedItems)){
            $unSelect = $all;
        } else {
            //已经选择的类型
            foreach ($selectedItems as $k => $v) {
                $selected[$v['item_id']] = $all[$v['item_id']];
            }

            $unSelect = array_diff($all,$selected);

        }

        return $this->render('distribution-item',['station' => $jobStation, 'group'=>$groupList, 'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect, 'id'=>$id, 'url'=>Url::to(['quality/save-job-item'])]]);
    }

    /**
     * 根据质检项组获取质检项（职能工位）
     * @author: liuFangShuo
     */
    public function actionGetItem()
    {
        if(Yii::$app->request->isAjax){
            $groupId = Yii::$app->request->post('group_id');
            $jobStationId = Yii::$app->request->post('job_station_id');

            //根据质检项组获取质检项
            $model = new QualityModel();

            if ($groupId == 0) {
                $items =$model->getQualityList();
                $itemList = array_column($items,'id');

            } else {
                $items =$model->getQualityItemByGroupId($groupId);
                $itemList = array_column($items,'item_id');
            }

            //根据职能工位获取质检项
            $selectItem = $model->getItemByJob($jobStationId);
            $itemListHave =  array_column($selectItem, 'item_id');

            $itemHave = array_diff($itemList,$itemListHave);

            $itemInfo = [];

            if(!empty($itemHave)){
                $itemInfo = $model->getQualityItemByIds($itemHave);
                $itemInfo = ArrayHelper::map($itemInfo, 'id', 'title');
            }

            return $this->ajaxReturn($itemInfo);
        }

    }

    /**
     * 保存职能工位质检项
     * @author: liuFangShuo
     */
    public function actionSaveJobItem()
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
                    $selectId[] =  $v[0];
                }
            }

            if (!empty($unSelect)) {
                foreach ($unSelect as $k => $v) {
                    $unSelectId[] =  $v[0];
                }
            }

            $model = new QualityModel();
            $res = $model->saveJobItem($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1, $model->getFirstError('name'));

        }

        return false;
    }

    /**
     *  关联职位
     * @author: liuFangShuo
     */
    public function actionRelateStation()
    {
        $id = Yii::$app->request->get('id',null);

        $model = new QualityModel();
        $jobStation = $model->getJobStationById($id);

        //分配质检流程
        if (empty($id)|| empty($jobStation)) {
            $this->redirect(Url::to(['quality/job-station']));
        }

        //获取职能工位所在车间的物理工位
        $stations = TypeWorkAreaAR::findAll(['workshop_id' => $jobStation->workshop_id, 'type_id' => $jobStation->type_id]);

        $canChoose = [];
        foreach ($stations as $k => $v) {
            $canChoose[] = $v->station_id;
        }

        //获取对应物理工位
        $stationsList = StationAR::find()->where(['id' => $canChoose, 'is_deleted' => STATUS_FALSE])->asArray()->all();

        $data = [];
        foreach ($stationsList as $m => $n) {
            $data[$n['work_area_id']][$n['id']] = $n;
        }

        //获取工区
        $workArea = WorkAreaAR::find()->where(['workshop_id' => $jobStation->workshop_id])->asArray()->all();
        $workAreaArr = ArrayHelper::map($workArea, 'id', 'name');

        //获取已选择的物理工位
        $chooseStations = JobStationRelateStationAR::find()->where(['job_station_id' => $id])->asArray()->all();
        $chooseStationsArr = array_column($chooseStations,'station_id');

        return $this->render('relate-station',['data' => $data,'station' => $jobStation, 'workArea' => $workAreaArr, 'id' => $id, 'chooseStations' => $chooseStationsArr]);

    }

    /**
     * 编辑关联工位
     * @author: liuFangShuo
     */
    public function actionEditRelateStation()
    {
        $id = Yii::$app->request->get('id', null);
        $post = Yii::$app->request->post('choose',null);

        //获取职能工位
        $model = new QualityModel();
        $jobStation = $model->getJobStationById($id);

        //职能不存在
        if (empty($jobStation) || empty($post)) {
            return $this->redirect(Url::to(['quality/job-station']));
        }

        //查一下在type_work_area里面有没有
        $stationList = $model->getTypeArea($jobStation->type_id,['station_id' => $post]);

        if (empty($stationList)) {
            return $this->redirect(Url::to(['quality/job-station']));
        }

        if($model->saveJobStation($jobStation,$stationList)){
            return $this->redirect(Url::to(['quality/job-station']));
        }

    }

    /**
     * 质检项组分配流程
     * @return string
     * @author: liuFangShuo
     */
    public function actionGroupDistributionProcess()
    {
        $id = Yii::$app->request->get('id',null);

        $model = new QualityModel();
        $group = $model->getQualityGroupById($id);

        //分配质检流程
        if (empty($id)|| empty($group)) {
            $this->redirect(Url::to(['quality/quality-group']));
        }

        //获取质检流程
        $processList = $model->qualityProcessList(QUALITY_PROCESS_GROUP);
        $all = ArrayHelper::map($processList,'id','name');

        //获取已选择的质检流程
        $selectedProcess = $model->getProcessByGroup($id);

        $selected = [];
        $unSelect = [];

        if(empty($selectedProcess)){
            $unSelect = $all;
        } else {
            //已经选择的类型
            foreach ($selectedProcess as $k => $v) {
                $selected[$v['process_id']] = $all[$v['process_id']];
            }

            $unSelect = array_diff($all,$selected);

        }

        return $this->render('group-distribution-process',['group' => $group, 'qualityItem' => ['all' => $all, 'selected' => $selected, 'unSelect' => $unSelect, 'id'=>$id, 'url'=>Url::to(['quality/save-group-process'])]]);
    }

    /**
     * 保存质检项组分配质检流程
     * @author: liuFangShuo
     */
    public function actionSaveGroupProcess()
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
                    $selectId[] =  $v[0];
                }
            }

            if (!empty($unSelect)) {
                foreach ($unSelect as $k => $v) {
                    $unSelectId[] =  $v[0];
                }
            }

            $model = new QualityModel();
            $res = $model->saveGroupProcess($id,$selectId,$unSelectId);

            if($res){
                return $this->ajaxReturn([]);
            }

            return $this->ajaxReturn([],1, $model->getFirstError('name'));

        }

        return false;
    }

    /**
     * @author: liuFangShuo
     */
    public function actionTaskInfoDetail()
    {
        //入场检定员 group_type=1 process_id task_id 找user_id
        if (Yii::$app->request->isAjax) {
            $taskId = Yii::$app->request->post('taskId', null);
            $groupId = Yii::$app->request->post('groupId', null);
            $isSplit = Yii::$app->request->post('isSplit', 0);  //是否是分解
            $type = Yii::$app->request->post('type', null);  //是否是分解


            //获取质检结果
            $model = new QualityModel();
            $itemList = $model->getQualityItemByGroupId($groupId);
            $itemArr = array_column($itemList,'item_id');

            $answerList = $model->getAnswer($taskId,$itemArr);

            $list = [];
            $view = 'task-detail';

            //获取相关的结果
            switch ($type){
                case QUALITY_ITEM_TYPE_BEGIN:   //入场检定
                    $view = 'task-detail-begin';
                    $answerReturn = [];
                    $userList = [];

                    foreach ($answerList as $k => $v) {

                        $item = [];
                        $item['name'] = $v['title'];
                        $item['standard'] = json_decode($v['standard'],true);

                        //根据不同题型获取对应
                        switch ($v['quality_item_type']) {
                            case QUALITY_TYPE_JUDGE:    //判断题

                                    $item['answer'] = !empty($v['choose_content']) ? $v['choose_content']:'';

                                    //如果备注不为空
                                    if (!empty($v['content'])) {
                                        $bz = json_decode($v['content'], true);
                                        $bzVal = '';

                                        if (count($bz) > 0) {
                                            foreach ($bz as $m) {
                                                if ($m['key'] == '备注') {
                                                    $bzArr[] = $m['value'];
                                                }
                                            }
                                            if (!empty($bzArr)) {
                                                $bzVal = implode(';', $bzArr);
                                            }
                                        }

                                        $item['answer'] .= "[备注：{$bzVal}]";
                                    }

                                break;
                            case QUALITY_TYPE_CHOOSE:   //选择题  (监造和专检只有选择题)
                                if (empty($v['process_id'])) {
                                    $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                    //如果备注不为空
                                    if (!empty($v['content'])) {
                                        $bz = json_decode($v['content'], true);
                                        $bzVal = '';

                                        if (count($bz) > 0) {
                                            foreach ($bz as $m) {
                                                if ($m['key'] == '备注') {
                                                    $bzArr[] = $m['value'];
                                                }
                                            }
                                            if (!empty($bzArr)) {
                                                $bzVal = implode(';', $bzArr);
                                            }
                                        }

                                        $item['answer'] .= "[备注：{$bzVal}]";
                                    }
                                } elseif ($v['process_id'] == 3) {  //专检
                                    $item['answer_computer'][] = $v['choose_content'];
                                } elseif ($v['process_id'] == 4) {  //监造
                                    $item['answer_computer_re'][] = $v['choose_content'];
                                }
                                break;
                            case QUALITY_TYPE_FILL:     //填空题
                                $item['answer'] = '';

                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {

                                            if ($m['key'] == '测量结果') {
                                                $item['answer'] = $m['value'];
                                            } elseif ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }

                                        }

                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }

                                break;
                            case QUALITY_TYPE_SELECT:   //多选题
                                $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {
                                            if ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }
                                        }

                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }


                                break;
                            case QUALITY_TYPE_COMB:     //混合题
                                $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {

                                            if ($m['key'] == '测量结果') {
                                                $item['answer'] .= '数值：' . $m['value'];
                                            } elseif ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }

                                        }

                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }

                                break;
                        }

                        $item['type'] = $v['quality_item_type'];

                        $answerReturn[$v['quality_item_id']] = isset($answerReturn[$v['quality_item_id']]) ? array_merge($answerReturn[$v['quality_item_id']],$item) : $item;

                    }

                    $list['answer'] = $answerReturn;

                    //获取监造|专检|录入人员
                    $exeRecord = $model->getTaskExeRecord($taskId,$groupId);

                    if (!empty($exeRecord)) {
                        foreach ($exeRecord as $y => $z){
                            if (empty($z['process_id'])) {

                                $userList['do_name'] = $z['doname'];
                            } elseif ($z['process_id'] == 3) {    //专检

                                $userList['do_computer'] = $z['doname'];
                            } elseif ($z['process_id'] == 4) {    //监造

                                $userList['do_computer_re'] = $z['doname'];
                            }
                        }
                    }


                    $list['user'] = $userList;

                    break;
                case QUALITY_ITEM_TYPE_DURING:  //在工位
                    $view = 'task-detail';
                    $answerReturn = [];
                    $userList = [];

                    //不需要分解
                    if (!$isSplit) {

                        foreach ($answerList as $k => $v) {

                            $item = [];
                            $item['name'] = $v['title'];
                            $item['standard'] = json_decode($v['standard'],true);

                            //根据不同题型获取对应
                            switch ($v['quality_item_type']) {
                                case QUALITY_TYPE_JUDGE:    //判断题

                                    switch ($v['process_id']) {
                                        case 1:             //自检
                                            $item['answer'] = !empty($v['choose_content']) ? $v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        case 2:             //互检
                                            $item['answer_each'] = !empty($v['choose_content']) ? $v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        default:
                                            break;
                                    }

                                    break;
                                case QUALITY_TYPE_CHOOSE:   //选择题  (监造和专检只有选择题)

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        case 2:
                                            $item['answer_each'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        case 3:
                                            $item['answer_computer'][] = $v['choose_content'];
                                            break;
                                        case 4:
                                            $item['answer_computer_re'][] = $v['choose_content'];
                                            break;
                                        default:
                                            break;

                                    }

                                    break;
                                case QUALITY_TYPE_FILL:     //填空题

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = '';
                                            $item['answer_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer'] = $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        case 2:
                                            $item['answer_each'] = '';
                                            $item['answer_each_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer_each'] = $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        default:
                                            break;
                                    }

                                    break;
                                case QUALITY_TYPE_SELECT:   //多选题

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        case 2:
                                            $item['answer_each'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        default:
                                            break;
                                    }

                                    break;
                                case QUALITY_TYPE_COMB:     //混合题

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer'] .= '数值：' . $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        case 2:
                                            $item['answer_each'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer_each'] .= '数值：' . $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                            }

                            $item['type'] = $v['quality_item_type'];

                            $answerReturn[$v['quality_item_id']] = isset($answerReturn[$v['quality_item_id']]) ? array_merge($answerReturn[$v['quality_item_id']],$item) : $item;

                        }

                        //获取监造|专检|录入人员
                        $exeRecord = $model->getTaskExeRecord($taskId,$groupId);

                        if (!empty($exeRecord)) {
                            foreach ($exeRecord as $y => $z){

                                if ($z['process_id'] == 3) {    //专检
                                    $userList['do_computer'] = $z['doname'];
                                } elseif ($z['process_id'] == 4) {    //监造
                                    $userList['do_computer_re'] = $z['doname'];
                                }
                            }
                        }

                        $list['user'] = $userList;

                    } else {
                        $view = 'task-detail-fj';

                        foreach ($answerList as $k => $v) {

                            $item = [];
                            $item['name'] = $v['title'];
                            $item['standard'] = json_decode($v['standard'],true);

                            //根据不同题型获取对应
                            switch ($v['quality_item_type']) {
                                case QUALITY_TYPE_JUDGE:    //判断题

                                    switch ($v['process_id']) {
                                        case 1:             //自检
                                            $item['answer'] = !empty($v['choose_content']) ? $v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        case 2:             //互检
                                            $item['answer_each'] = !empty($v['choose_content']) ? $v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }

                                        default:
                                            break;
                                    }

                                    break;
                                case QUALITY_TYPE_CHOOSE:   //选择题  (监造和专检只有选择题)

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        case 2:
                                            $item['answer_each'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }
                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        case 3:
                                            $item['answer_computer'][] = $v['choose_content'];
                                            break;
                                        case 4:
                                            $item['answer_computer_re'][] = $v['choose_content'];
                                            break;
                                        default:
                                            break;

                                    }

                                    break;
                                case QUALITY_TYPE_FILL:     //填空题

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = '';
                                            $item['answer_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer'] = $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        case 2:
                                            $item['answer_each'] = '';
                                            $item['answer_each_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer_each'] = $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        default:
                                            break;
                                    }

                                    break;
                                case QUALITY_TYPE_SELECT:   //多选题

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        case 2:
                                            $item['answer_each'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }
                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }
                                            break;
                                        default:
                                            break;
                                    }

                                    break;
                                case QUALITY_TYPE_COMB:     //混合题 分解都是混合题

                                    switch ($v['process_id']) {
                                        case 1:
                                            $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer'] .= '数值：' . $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer'] .= "[备注：{$bzVal}]";
                                            }

                                            break;
                                        case 2:
                                            $item['answer_each'] = !empty($v['choose_content'])?$v['choose_content']:'';
                                            $item['answer_each_name'] = $v['name'];

                                            if (!empty($v['content'])) {
                                                $bz = json_decode($v['content'], true);
                                                $bzVal = '';

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {

                                                        if ($m['key'] == '测量结果') {
                                                            $item['answer_each'] .= '数值：' . $m['value'];
                                                        } elseif ($m['key'] == '备注') {
                                                            $bzArr[] = $m['value'];
                                                        }

                                                    }

                                                    if (!empty($bzArr)) {
                                                        $bzVal = implode(';', $bzArr);
                                                    }
                                                }

                                                $item['answer_each'] .= "[备注：{$bzVal}]";
                                            }

                                            break;

                                        case 5:     //分解 （只有混合题）
                                            $item['answer_fj'] = !empty($v['choose_content']) ? $v['choose_content']:'合格';//默认合格
                                            $item['answer_fj_do'] = '';

                                            //分解人员签名
                                            $userList['do_name'] = isset($userList['do_name'])?$userList['do_name']:$v['name'];

                                            //如果备注不为空
                                            if (!empty($v['content']) && !empty($v['choose_content']) && $v['choose_content'] == '不合格') {
                                                $bz = json_decode($v['content'], true);

                                                if (count($bz) > 0) {
                                                    foreach ($bz as $m) {
                                                        if ($m['key'] == '检查结果') {
                                                            $item['answer_fj'] = $m['value'];
                                                        }
                                                        elseif ($m['key'] == '处理方法') {
                                                            $item['answer_fj_do'] = $m['value'];
                                                        }
                                                    }

                                                }

                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                            }

                            $item['type'] = $v['quality_item_type'];

                            $answerReturn[$v['quality_item_id']] = isset($answerReturn[$v['quality_item_id']]) ? array_merge($answerReturn[$v['quality_item_id']],$item) : $item;

                        }

                        //var_dump($answerReturn);exit;

                        //获取监造|专检|录入人员
                        $exeRecord = $model->getTaskExeRecord($taskId,$groupId);

                        if (!empty($exeRecord)) {
                            foreach ($exeRecord as $y => $z){

                                if ($z['process_id'] == 3) {    //专检
                                    $userList['do_computer'] = $z['doname'];
                                } elseif ($z['process_id'] == 4) {    //监造
                                    $userList['do_computer_re'] = $z['doname'];
                                }
                            }
                        }

                        $list['user'] = $userList;

                    }

                    $list['answer'] = $answerReturn;

                    break;
                case QUALITY_ITEM_TYPE_OVER:    //整车质检(只有专检和监造)
                    $view = 'task-detail-end';
                    $answerReturn = [];
                    $userList = [];

                    foreach ($answerList as $k => $v) {

                        $item = [];
                        $item['name'] = $v['title'];
                        $item['standard'] = json_decode($v['standard'],true);

                        //根据不同题型获取对应
                        switch ($v['quality_item_type']) {
                            case QUALITY_TYPE_JUDGE:    //判断题

                                $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                //如果备注不为空
                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {
                                            if ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }
                                        }
                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }

                                break;
                            case QUALITY_TYPE_CHOOSE:   //选择题  (监造和专检只有选择题)
                                if (empty($v['process_id'])) {
                                    $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                    //如果备注不为空
                                    if (!empty($v['content'])) {
                                        $bz = json_decode($v['content'], true);
                                        $bzVal = '';

                                        if (count($bz) > 0) {
                                            foreach ($bz as $m) {
                                                if ($m['key'] == '备注') {
                                                    $bzArr[] = $m['value'];
                                                }
                                            }
                                            if (!empty($bzArr)) {
                                                $bzVal = implode(';', $bzArr);
                                            }
                                        }

                                        $item['answer'] .= "[备注：{$bzVal}]";
                                    }
                                } elseif ($v['process_id'] == 3) {  //专检
                                    $item['answer_computer'][] = $v['choose_content'];
                                } elseif ($v['process_id'] == 4) {  //监造
                                    $item['answer_computer_re'][] = $v['choose_content'];
                                }

                                break;
                            case QUALITY_TYPE_FILL:     //填空题
                                $item['answer'] = '';

                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {

                                            if ($m['key'] == '测量结果') {
                                                $item['answer'] = $m['value'];
                                            } elseif ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }

                                        }

                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }

                                break;
                            case QUALITY_TYPE_SELECT:   //多选题
                                $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {
                                            if ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }
                                        }

                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }


                                break;
                            case QUALITY_TYPE_COMB:     //混合题
                                $item['answer'] = !empty($v['choose_content'])?$v['choose_content']:'';

                                if (!empty($v['content'])) {
                                    $bz = json_decode($v['content'], true);
                                    $bzVal = '';

                                    if (count($bz) > 0) {
                                        foreach ($bz as $m) {

                                            if ($m['key'] == '测量结果') {
                                                $item['answer'] .= '数值：' . $m['value'];
                                            } elseif ($m['key'] == '备注') {
                                                $bzArr[] = $m['value'];
                                            }

                                        }

                                        if (!empty($bzArr)) {
                                            $bzVal = implode(';', $bzArr);
                                        }
                                    }

                                    $item['answer'] .= "[备注：{$bzVal}]";
                                }

                                break;
                        }

                        $item['type'] = $v['quality_item_type'];

                        $answerReturn[$v['quality_item_id']] = isset($answerReturn[$v['quality_item_id']]) ? array_merge($answerReturn[$v['quality_item_id']],$item) : $item;

                    }

                    $list['answer'] = $answerReturn;

                    //获取监造|专检|录入人员
                    $exeRecord = $model->getTaskExeRecord($taskId,$groupId);

                    if (!empty($exeRecord)) {
                        foreach ($exeRecord as $y => $z){
                            if (empty($z['process_id'])) {

                                $userList['do_name'] = $z['doname'];
                            } elseif ($z['process_id'] == 3) {    //专检

                                $userList['do_computer'] = $z['doname'];
                            } elseif ($z['process_id'] == 4) {    //监造

                                $userList['do_computer_re'] = $z['doname'];
                            }
                        }
                    }

                    $list['user'] = $userList;
                    break;
                default:
                    break;

            }

            return $this->renderAjax($view, ['list' => $list]);

        }

        return false;
    }
}

