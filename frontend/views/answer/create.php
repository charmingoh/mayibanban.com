<?php

use frontend\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $question common\models\Question */
/* @var $answerForm frontend\models\AnswerForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            添加回答
        </h3>
    </div>
    <div class="panel-body">
        <div class="answer-form">
            <?php $form = ActiveForm::begin([
                'action' => ['/answer/create'],
                'method' => 'post',
            ]); ?>

            <?= Html::activeHiddenInput($answerForm, 'question_id', ['value' => $question->id]); ?>
            <?= $form->field($answerForm, 'url')->textInput([
                'placeholder' => '复制粘贴: http://...'
            ]) ?>
            <?= $form->field($answerForm, 'title')->textInput([
                'placeholder' => '外链资源的标题'
            ]) ?>
            <?= $form->field($answerForm, 'digest')->textarea([
                'rows'        => 3,
                'placeholder' => '分享心得/方法/亮点/槽点…'
            ]) ?>

            <div class="form-group">
                <?php
                if (Yii::$app->user->isGuest) {
                    echo Html::submitButton('添加回答', ['class' => 'btn btn-success disabled']);
                    echo Html::a('请先登录后回答', ['user/login']);
                } else {
                    echo Html::submitButton('添加回答', ['class' => 'btn btn-success']);
                }
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?= Html::jsFile('@web/static/answer/create.js'); ?>
