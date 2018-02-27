<?php

namespace app\modules\admin\controllers;

use app\models\ar\QualityInspectionItemAR;
use app\models\QualityModel;
use Yii;
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
}
