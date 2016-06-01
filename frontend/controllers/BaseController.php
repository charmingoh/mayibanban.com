<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/4/26
 * Time: 下午6:07
 */

namespace frontend\controllers;


use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    /**
     * @param $result array
     * @return mixed
     */
    protected function json($result)
    {
        //Yii::$app->getRequest()->getIsAjax()
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @param bool $isSuccess
     * @param null $data
     * @param null $msg
     * @return mixed
     */
    protected function jsonResult($isSuccess = false, $data = null, $msg = null)
    {
        $result = [
            'isSuccess' => $isSuccess,
            'data' => $data,
            'msg' => $msg,
        ];

        return $this->json($result);
    }
}