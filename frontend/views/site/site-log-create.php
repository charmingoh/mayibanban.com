<?php

use frontend\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SiteLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-log-create">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="site-log-form">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'detail')->textarea([
                    'rows' => 3,
                    'maxlength' => 256,
                    'placeholder' => 'improve/add/fix...'
                ]) ?>
                <?= $form->field($model, 'version')->textInput([
                    'maxlength' => 16,
                    'placeholder' => 'just name a version...',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('新建上线记录', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
