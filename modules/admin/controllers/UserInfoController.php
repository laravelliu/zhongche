<?php

namespace app\modules\admin\controllers;

use app\common\filters\PermissionFilter;
use app\models\ar\DepartmentAR;
use app\models\ar\RoleAR;
use app\models\ar\UserAR;
use Yii;
use app\models\ar\UserRoleAR;

class UserInfoController extends BaseController
{
    public function appendBehaviors()
    {
        return [
            'permission' => [
                'class' => PermissionFilter::className(),
                'except' => ['index'],
                'failUrl' => '/login'
            ],
        ];
    }

    public function actionIndex()
    {
        //获取用户角色
        $roleName = '无';
        $permission = 0;
        $roleList = UserRoleAR::findAll(['user_id' => Yii::$app->user->identity->id]);
        if (!empty($roleList)) {
            $roles = RoleAR::find()->select('name')->where(['id' => array_column($roleList,'role_id')])->asArray()->all();
            $roles = array_column($roles, 'name');
            $roleName = implode('|',$roles);
        }

        //获取部门
        $department = '无';
        $departmentInfo = DepartmentAR::findOne(['id' => Yii::$app->user->identity->department_id]);

        if (!empty($departmentInfo)) {
            $department = $departmentInfo->name;
        }

        if(Yii::$app->request->isPost){
            $model = Yii::$app->user->identity;
            $model->setScenario('updateInfo');
            if ($model->load($post = Yii::$app->request->post()) && $model->validate()) {
                $model->save(false);
            } else {
                $model->getErrors();
            }

        }

        return $this->render('index',['roleName'=>$roleName, 'department' => $department]);
    }

    /**
     * 保存更改信息
     * @author: liuFangShuo
     */
    public function actionSaveInfo()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
        }
    }

    /**
     * 更改头像
     * @author: liuFangShuo
     */
    public function actionChangePhoto()
    {
        if(Yii::$app->request->isAjax){
            $url = Yii::$app->request->post('url', null);

            if(empty($url)){
               return $this->ajaxReturn([],1,'url不能为空');
            }

            Yii::$app->user->identity->admin_photo = $url;
            $res = Yii::$app->user->identity->save(false);

            if($res){
                return $this->ajaxReturn();
            }

            return $this->ajaxReturn([],1,'更新失败');
        }
        return false;
    }

}
