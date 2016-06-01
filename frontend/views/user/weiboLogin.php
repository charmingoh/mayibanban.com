<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use frontend\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '蚂蚁搬搬 - 登录';
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne">
                            微博直接登录 · 无需注册
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?= Html::a(Html::img(['@web/static/img/weibo_login_173_32.png'], [
                            'class' => 'img-responsive center-block',
                            'alt' => 'QQ 登录',
                            'style' => 'margin-top: 30px; margin-bottom: 30px;',
                        ]), ['user/auth', 'authclient' => 'Weibo']); ?>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                           href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            邮箱登录
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form'
                        ]) ?>

                        <?= $form->field($model, 'email')->textInput([
                            'placeholder' => $model->getAttributeLabel('email'),
                        ]) ?>

                        <?= $form->field($model, 'password')->passwordInput([
                            'placeholder' => $model->getAttributeLabel('password'),
                        ]) ?>

                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class=\"checkbox\">\n" . Html::a('忘记密码?',
                                    ['user/request-password-reset'],
                                    ['class' => 'pull-right']) . "{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>"
                        ]) ?>

                        <?= Html::submitButton('登录', ['class' => 'btn btn-block btn-primary']) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>