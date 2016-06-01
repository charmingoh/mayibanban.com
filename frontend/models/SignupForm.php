<?php
namespace frontend\models;

use common\models\LocalAuth;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email', 'message' => '请填写正确的邮箱地址.'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\LocalAuth', 'message' => '该邮箱已经被注册.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'email'    => '邮箱',
            'password' => '密码',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        $user->generateValue();

        $transaction = User::getDb()->beginTransaction();
        if (!$user->save()) {
            return null;
        }

        $localAuth = new LocalAuth([
            'user_id' => $user->id,
            'email' => $this->email,
        ]);
        $localAuth->setPassword($this->password);
        $localAuth->generatePasswordResetToken();

        if ($localAuth->save()) {
            $transaction->commit();
            return $user;
        } else {
            return null;
        }
    }
}
