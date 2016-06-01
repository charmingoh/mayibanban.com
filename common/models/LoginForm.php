<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    //private $_user;
    private $_localAuth;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            ['email', 'email', 'message' => '请填写正确的邮箱地址.'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Returns the attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'email'      => '邮箱',
            'password'   => '密码',
            'rememberMe' => '记住密码',
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $localAuth = $this->getLocalAuth();
            if (!$localAuth || !$localAuth->validatePassword($this->password)) {
                $this->addError($attribute, '邮箱或密码不正确');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $rememberMeDuration = Yii::$app->params['user.rememberMeDuration'];
            return Yii::$app->user->login($this->getLocalAuth()->user, $this->rememberMe ? $rememberMeDuration : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
//    protected function getUser()
//    {
//        if ($this->_user === null) {
//            /*$this->_user = User::findByEmail($this->email);*/
//            $this->_user = User::findOne($this->getLocalAuth()->user_id);
//        }
//
//        return $this->_user;
//    }

    /**
     * Finds user by [[email]]
     *
     * @return LocalAuth|null
     */
    protected function getLocalAuth()
    {
        if ($this->_localAuth === null) {
            $this->_localAuth = LocalAuth::findByEmail($this->email);
        }

        return $this->_localAuth;
    }
}
