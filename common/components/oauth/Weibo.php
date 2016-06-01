<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/5/12
 * Time: 下午4:00
 */

namespace common\components\oauth;

use yii\authclient\OAuth2;

/**
 * Weibo allows authentication via Weibo OAuth.
 *
 * In order to use Weibo OAuth you must register your application at <http://open.weibo.com/>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'weibo' => [
 *                 'class' => 'yujiandong\authclient\Weibo',
 *                 'clientId' => 'wb_key',
 *                 'clientSecret' => 'wb_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see http://open.weibo.com/
 * @see http://open.weibo.com/wiki/
 *
 * @author Jiandong Yu <flyyjd@gmail.com>
 */
class Weibo extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.weibo.com';

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'username' => 'name',
        ];
    }

    /**
     * @inheritdoc
     * id: 用户UID
     * screen_name: 微博昵称
     * name: 友好显示名称，如Bill Gates(此特性暂不支持)
     * province: 省份编码（参考省份编码表）
     * city: 城市编码（参考城市编码表）
     * location：地址
     * description: 个人描述
     * url: 用户博客地址
     * profile_image_url: 自定义图像
     * domain: 用户个性化URL
     * gender: 性别,m--男，f--女,n--未知
     * followers_count: 粉丝数
     * friends_count: 关注数
     * statuses_count: 微博数
     * favourites_count: 收藏数
     * created_at: 创建时间
     * following: 是否已关注(此特性暂不支持)
     * verified: 加V标示，是否微博认证用户
     */
    protected function initUserAttributes()
    {
        $openid = $this->api('oauth2/get_token_info', 'POST');
        $userAttributes = $this->api("2/users/show.json", 'GET', ['uid' => $openid['uid']]);
        //$userAttributes['name'] = $userAttributes['screen_name'];
        $userAttributes['gender'] = $userAttributes['gender'] == 'm' ? 'male' : 'female';
        $userAttributes['avatar'] = $userAttributes['profile_image_url'];
        $userAttributes['status'] = null;

        return $userAttributes;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weibo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return '微博';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 800,
            'popupHeight' => 500,
        ];
    }
}