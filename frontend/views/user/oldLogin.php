<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use frontend\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name . ' - 登录';
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">邮箱登录</h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form'
                ]) ?>

                <?= $form->field($model, 'email')->textInput([
                    'placeholder' => $model->getAttributeLabel('email'),
                    'autofocus'   => true,
                ]) ?>

                <?= $form->field($model, 'password')->passwordInput([
                    'placeholder' => $model->getAttributeLabel('password'),
                ]) ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"checkbox\">\n" . Html::a('忘记密码?', ['user/request-password-reset'],
                            ['class' => 'pull-right']) . "{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>"
                ]) ?>

                <?= Html::submitButton('登录', ['class' => 'btn btn-block btn-primary']) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="panel-footer">
                <?= Html::a(Html::img('@web/static/img/login_weibo_24_24.png') . ' 微博登录', ['user/auth', 'authclient' => 'Weibo']); ?>
                <?= Html::a(Html::img('@web/static/img/qq_login_24_24.png') . ' QQ 登录', ['user/auth', 'authclient' => 'QQ'], ['class' => 'pull-right']); ?>
            </div>
        </div>
    </div>
</div>