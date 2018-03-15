<?php

namespace app\modules\admin\controllers;

use app\models\ar\RoleAR;
use app\models\StaffModel;
use Yii;
use yii\helpers\Url;

class RoleController extends BaseController
{
    /**
     * 角色管理
     * @return string
     * @author: liuFangShuo
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取角色列表
     * @return object
     * @author: liuFangShuo
     */
    public function actionGetRole()
    {
        $model = new StaffModel();
        $roleList = $model->getRole();

        if(!empty($roleList)){
            foreach ($roleList as $k => $v){
                $roleList[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $roleList[$k]['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
            }
        }

        return $this->ajaxReturn($roleList);
    }

    /**
     * 添加角色
     * @return string
     * @author: liuFangShuo
     */
    public function actionAddRole()
    {
        $model = new RoleAR();
        $model->setScenario('create');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model ->validate()){
                if($model->saveRole()){
                    return $this->redirect(Url::to(['role/index']));
                }
            }

            $model->getErrors();
        }

        return $this->render('add-role',['model' => $model]);
    }

    /**
     * 编辑角色
     * @return string
     * @author: liuFangShuo
     */
    public function actionEditRole()
    {
        $id = Yii::$app->request->get('id', null);
        $model = RoleAR::findOne(['is_deleted' => STATUS_FALSE, 'id' => $id]);

        if(empty($id) || empty($model)){
            return $this->redirect(Url::to(['role/index']));
        }

        $model->setScenario('update');

        if(Yii::$app->request->isPost){
            if($model->load($post = Yii::$app->request->post()) && $model ->validate()){
                if($model->saveRole()){
                    return $this->redirect(Url::to(['role/index']));
                }
            }

            $model->getErrors();
        }

        return $this->render('edit-role', ['model' => $model]);
    }

    /**
     * 角色分配
     * @author: liuFangShuo
     */
    public function actionDistribution()
    {
        return $this->render('distribution');
    }
}
