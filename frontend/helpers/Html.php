<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/4/28
 * Time: 上午10:38
 */

namespace frontend\helpers;


use yii\helpers\BaseHtml;

class Html extends BaseHtml
{
    /**
     * @inheritDoc
     */
    public static function cssFile($url, $options = [])
    {
        return parent::cssFile($url . '?v=' . \Yii::$app->version, $options);
    }

    /**
     * @inheritDoc
     */
    public static function jsFile($url, $options = [])
    {
        return parent::jsFile($url . '?v=' . \Yii::$app->version, $options);
    }


}