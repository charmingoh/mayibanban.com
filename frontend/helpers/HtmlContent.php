<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/4/28
 * Time: 下午7:13
 */

namespace frontend\helpers;


use Yii;
use yii\httpclient\Client;

class HtmlContent
{
    public static function getTitleByUrl($url)
    {
        /*$client = new Client();
        $htmlContent = $client->get($url)->send()->getContent();*/
        /*$htmlContent = file_get_contents($url);*/
        $contextOptions = array(
            "ssl" => array(
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ),
        );

        $htmlContent = file_get_contents($url, false, stream_context_create($contextOptions));
        return self::parseTitleFrom($htmlContent);
    }

    /**
     * @param $htmlContent
     * @return mixed
     */
    private static function parseTitleFrom($htmlContent)
    {
        $titleBegin = strpos($htmlContent, '<title>') + 7;
        $titleEnd = strpos($htmlContent, '</title>');
        $titleLength = $titleEnd - $titleBegin;
        $title = trim(substr($htmlContent, $titleBegin, $titleLength));

        try {
            json_encode($title);
        } catch (\Exception $e) {
            //do nothing
        }
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            /*case JSON_ERROR_UTF8:
                $title = '不能解析出标题, 请手动填写链接标题吧~';
                break;*/
            default:
                try {
                    $title = iconv("GBK", "UTF-8", $title);
                } catch (\Exception $e) {
                    $title = '不能解析出标题, 请手动填写链接标题吧~';
                }
                break;
        }

        return self::decodeHtmlSpecialChars($title);
    }

    private static function decodeHtmlSpecialChars($title)
    {
        $title = str_replace('&nbsp;', ' ', $title);
        $title = str_replace('&quot;', '"', $title);
        $title = str_replace('&#39;', "'", $title);
        $title = str_replace('&lt;', '<', $title);
        $title = str_replace('&gt;', '>', $title);
        $title = str_replace('&amp;', '&', $title);

        return $title;
    }
}