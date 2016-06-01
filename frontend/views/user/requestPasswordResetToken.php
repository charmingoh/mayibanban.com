<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use frontend\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '重置密码 - 蚂蚁搬搬'
?>
<div class="user-request-password-reset">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">填写注册邮箱重置密码</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email')->textInput([
                        'placeholder' => '重置密码的链接将会发送到注册邮箱里',
                        'autofocus' => true,
                    ]); ?>

                    <div class="form-group">
                        <?= Html::submitButton('发送', ['class' => 'btn btn-block btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

