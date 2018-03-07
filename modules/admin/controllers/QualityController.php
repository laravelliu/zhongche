<?php

namespace app\modules\admin\controllers;

use app\models\ar\ProcessAR;
use app\models\ar\QualityInspectionGroupAR;
use app\models\ar\QualityInspectionItemAR;
use app\models\ar\TypeAR;
use app\models\QualityModel;
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

            $qualityTypes = ArrayHelper::map($qualityList,'id', 'name');

            foreach ($qualityList as $k =>$v){
                $qualityList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $qualityList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $qualityList[$k]['pid'] = isset($qualityTypes[$v['pid']])?$qualityTypes[$v['pid']] : '无';
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
        $qualityModel = new QualityModel();

        return $this->render('add-type',['model' => $model, 'qualityModel' => $qualityModel]);
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

        $qualityModel = new QualityModel();
        $qualityListInfo = $qualityModel->qualityTypeList();
        unset($qualityListInfo[$id]);

        return $this->render('edit-type',['model' => $model, 'qualityTypeList' => $qualityListInfo]);
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
                'title' => '缺少质检类别',
                'content' => '没有质检类别，质检项组需要质检类别信息作为前置条件，请添加质检类别。',
                'button' => '添加质检类别',
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
        $qyalityType = $model->getQualityTypeByLevel(1);
        $qyalityTypeList = ArrayHelper::map($qyalityType,'id','name');


        if(!empty($qualityGroupList)){
            foreach ($qualityGroupList as $k => $v){
                $qualityGroupList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $qualityGroupList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
                $qualityGroupList[$k]['type'] = $qyalityTypeList[$v['type_id']];
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
        $qualityType = $qualutyModel->getQualityTypeByLevel(1);
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
        $qualityType = $qualutyModel->getQualityTypeByLevel(1);
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
                    return $this->redirect(Url::to(['quality/quality-group']));
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

    }
}

