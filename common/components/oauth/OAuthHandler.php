<?php

namespace common\components\oauth;

use common\models\OAuth;
use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * OAuthHandler handles successful authentification via Yii oauth component
 */
class OAuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $id = ArrayHelper::getValue($attributes, 'id');
        $name = ArrayHelper::getValue($attributes, 'name');
        $gender = ArrayHelper::getValue($attributes, 'gender');
        $avatar = ArrayHelper::getValue($attributes, 'avatar');
        $userInfo = json_encode($attributes);

        /** @var $oauth OAuth */
        $oauth = OAuth::find()->andWhere([
            'source'    => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($oauth) { // login
                /** @var User $user */
                $user = $oauth->user;
                $this->updateUserInfo($user);
                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
            } else { // signup
                $user = new User([
                    'name'     => $name,
                    'gender'   => $gender,
                    'avatar'   => $avatar,
                ]);
                $user->generateValue();

                $transaction = User::getDb()->beginTransaction();

                if ($user->save()) {
                    $oauth = new OAuth([
                        'user_id'   => $user->id,
                        'source'    => $this->client->getId(),
                        'source_id' => (string)$id,
                        'user_info' => $userInfo,
                    ]);
                    if ($oauth->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', '不能保存 {client} 账户: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($oauth->getErrors()),
                            ]),
                        ]);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', '不能保存用户信息: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($user->getErrors()),
                        ]),
                    ]);
                }
            }
        } else { // user already logged in
            if (!$oauth) { // add oauth provider
                $oauth = new OAuth([
                    'user_id'   => Yii::$app->user->id,
                    'source'    => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                    'user_info' => $userInfo,
                ]);
                if ($oauth->save()) {
                    /** @var User $user */
                    $user = $oauth->user;
                    $this->updateUserInfo($user);
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', '成功关联 {client} 账户.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', '不能绑定 {client} 账户: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($oauth->getErrors()),
                        ]),
                    ]);
                }
            } else { // there's existing oauth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        '此 {client} 账户已经被绑定, 绑定失败.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        $attributes = $this->client->getUserAttributes();
        $name = ArrayHelper::getValue($attributes, 'name');
        $gender = ArrayHelper::getValue($attributes, 'gender');
        $avatar = ArrayHelper::getValue($attributes, 'avatar');

        $isChanged = false;
        if ($user->name == null && $name) {
            $user->name = $name;
            $isChanged = true;
        }

        if ($user->gender == null) {
            $user->gender = $gender;
            $isChanged = true;
        }

        if ($user->avatar == null) {
            $user->avatar = $avatar;
            $isChanged = true;
        }

        if ($isChanged) {
            $user->save();
        }
    }
}