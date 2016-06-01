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
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne">
                            社交账户直接登录 · 无需注册
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2" style="padding-top: 40px; padding-bottom: 40px;">
                                <?= Html::a(Html::img(['@web/static/img/login_qq_70_70.png'], [
                                    'alt' => 'QQ 登录',
                                    'title' => 'QQ 登录',
                                    'data' => [
                                        'toggle' => 'tooltip',
                                        'placement' => 'top',
                                    ]
                                ]), ['user/auth', 'authclient' => 'QQ']); ?>
                                <?= Html::a(Html::img(['@web/static/img/login_weibo_70_70.png'], [
                                    'class' => 'pull-right',
                                    'alt' => '微博登录',
                                    'title' => '微博登录',
                                    'data' => [
                                        'toggle' => 'tooltip',
                                        'placement' => 'top',
                                    ]
                                ]), ['user/auth', 'authclient' => 'Weibo']); ?>
                            </div>
                        </div>
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