<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/20
 * Time: 16:11
 */

namespace app\models;


use app\models\ar\UserAR;
use yii\base\Model;

class UserModel extends Model
{
    /**
     * 获取用户列表
     * @author: liuFangShuo
     */
    public function getUserList()
    {
        $userList = UserAR::find()->where(['is_deleted' => STATUS_FALSE])->asArray()->all();
        return $userList;
    }
}