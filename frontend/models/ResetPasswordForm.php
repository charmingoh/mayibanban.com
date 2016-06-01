<?php
namespace frontend\models;

use common\models\LocalAuth;
use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    //private $_user;
    /**
     * @var \common\models\LocalAuth
     */
    private $_localAuth;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('口令不能为空');
        }
        /*$this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }*/
        $this->_localAuth = LocalAuth::findByPasswordResetToken($token);
        if (!$this->_localAuth) {
            throw new InvalidParamException('错误的口令');
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'password' => '新密码',
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $localAuth = $this->_localAuth;
        $localAuth->setPassword($this->password);
        $localAuth->removePasswordResetToken();

        return $localAuth->save(false);

        /*$user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);*/
    }
}
