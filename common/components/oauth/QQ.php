<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/5/7
 * Time: 上午1:10
 */

namespace common\components\oauth;

use yii\authclient\OAuth2;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use Yii;

/**
 * QQ allows authentication via QQ OAuth.
 *
 * In order to use QQ OAuth you must register your application at <http://connect.qq.com/>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'qq' => [
 *                 'class' => 'yujiandong\authclient\Qq',
 *                 'clientId' => 'qq_appid',
 *                 'clientSecret' => 'qq_appkey',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see http://connect.qq.com/
 * @see http://wiki.connect.qq.com/
 *
 * @author Jiandong Yu <flyyjd@gmail.com>
 */
class QQ extends OAuth2
{

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://graph.qq.com';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'get_user_info',
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function buildAuthUrl(array $params = [])
    {
        $authState = $this->generateAuthState();
        $this->setState('authState', $authState);
        $params['state'] = $authState;
        return parent::buildAuthUrl($params);
    }

    /**
     * @inheritdoc
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        $authState = $this->getState('authState');
        if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
            throw new HttpException(400, 'Invalid auth state parameter.');
        } else {
            $this->removeState('authState');
        }

        return parent::fetchAccessToken($authCode, $params);

    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            //'name' => 'nickname',
        ];
    }

    /**
     * @inheritdoc
     * ret	返回码
     * msg	如果ret<0，会有相应的错误信息提示，返回数据全部用UTF-8编码。
     * nickname	用户在QQ空间的昵称。
     * figureurl	大小为30×30像素的QQ空间头像URL。
     * figureurl_1	大小为50×50像素的QQ空间头像URL。
     * figureurl_2	大小为100×100像素的QQ空间头像URL。
     * figureurl_qq_1	大小为40×40像素的QQ头像URL。
     * figureurl_qq_2	大小为100×100像素的QQ头像URL。需要注意，不是所有的用户都拥有QQ的100x100的头像，但40x40像素则是一定会有。
     * gender	性别。 如果获取不到则默认返回"男"
     * is_yellow_vip	标识用户是否为黄钻用户（0：不是；1：是）。
     * vip	标识用户是否为黄钻用户（0：不是；1：是）
     * yellow_vip_level	黄钻等级
     * level	黄钻等级
     * is_yellow_year_vip	标识是否为年费黄钻用户（0：不是； 1：是）
     */
    protected function initUserAttributes()
    {
        $user = $this->api('oauth2.0/me', 'GET');
        if (isset($user['error'])) {
            throw new HttpException(400, $user['error'] . ':' . $user['error_description']);
        }
        $userAttributes = $this->api(
            "user/get_user_info",
            'GET',
            [
                'oauth_consumer_key' => $user['client_id'],
                'openid'             => $user['openid'],
            ]
        );

        $userAttributes['id'] = $user['openid'];
        $userAttributes['name'] = $userAttributes['nickname'];
        $userAttributes['gender'] = $userAttributes['gender'] == '男' ? 'male' : 'female';
        $userAttributes['avatar'] = isset($userAttributes['figureurl_qq_2']) ? $userAttributes['figureurl_qq_2'] : $userAttributes['figureurl_qq_1'];
        
        return $userAttributes;
    }

    /**
     * @inheritdoc
     */
    protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
    {
        if ($contentType === self::CONTENT_TYPE_AUTO && strpos($rawResponse, "callback(") === 0) {
            $count = 0;
            $jsonData = preg_replace('/^callback\(\s*(\\{.*\\})\s*\);$/is', '\1', $rawResponse, 1, $count);
            if ($count === 1) {
                $rawResponse = $jsonData;
                $contentType = self::CONTENT_TYPE_JSON;
            }
        }
        return parent::processResponse($rawResponse, $contentType);
    }

    /**
     * Generates the auth state value.
     * @return string auth state value.
     */
    protected function generateAuthState()
    {
        return sha1(uniqid(get_class($this), true));
    }

    /**
     * @inheritdoc
     */
    protected function defaultReturnUrl()
    {
        $params = $_GET;
        unset($params['code']);
        unset($params['state']);
        $params[0] = Yii::$app->controller->getRoute();

        return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'QQ';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'QQ';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth'  => 800,
            'popupHeight' => 500,
        ];
    }
}
