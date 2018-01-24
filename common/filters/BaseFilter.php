<?php
namespace  app\common\filters;
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2017/4/13
 * Time: 09:40
 */

use yii\base\ActionFilter;
use yii\helpers\Url;

class BaseFilter extends ActionFilter
{
    //错误跳转链接
    public $failUrl = '/site/error';


    public function __construct($config = array())
    {
        parent::__construct($config);

    }

    //ajax的错误信息
    protected $_ajaxErrorMsg = array(
        'code' => 0,
        'message' => 'error message'
    );

    /**
     * 错误操作
     * @param string $failUrl
     * @param string $msg
     * @param string $re
     * @return bool
     * @author: liuFangShuo
     */
    public function fail($failUrl = '', $msg = '', $re = '')
    {
        if ($failUrl) {
            $this->failUrl = $failUrl;
        }

        if (\Yii::$app->request->isAjax) {

            if ('/login' == $failUrl) {
                \Yii::$app->getResponse()->redirect(Url::to([$this->failUrl, 're' => $re], 302))->send();
            } else {

                $headers = \Yii::$app->response->headers;
                $headers->add("Content-type", 'application/json');
                echo json_encode($this->_ajaxErrorMsg);
            }

            return false;

        } else {
            $re = empty($re) ? \Yii::$app->request->getUrl() : $re;
            \Yii::$app->getResponse()->redirect(Url::to([$this->failUrl, 're' => $re], 302))->send();
        }
    }
}