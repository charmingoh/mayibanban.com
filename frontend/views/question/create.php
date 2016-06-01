<?php

use frontend\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\QuestionForm */
/* @var $form ActiveForm */
$this->title = '提个问题 - ' . Yii::$app->name;
?>
<div class="question-create">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput([
        'placeholder' => '一句话描述你的问题',
    ]) ?>
    <?= $form->field($model, 'detail')->textarea([
        'rows'         => 3,
        'placeholder' => '请描述你的问题',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>