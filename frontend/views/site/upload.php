<?php

/* @var $this yii\web\View */
/* @var $model \frontend\models\UploadForm */
use yii\widgets\ActiveForm;
use frontend\helpers\Html;

$this->title = 'Upload Image';
?>

<div class="site-upload">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= Html::submitButton('上传', ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end() ?>
</div>


