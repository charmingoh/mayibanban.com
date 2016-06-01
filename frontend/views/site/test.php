<?php
/* @var $model \frontend\models\UploadForm;*/
use frontend\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="">
    hello
</div>
<div class="site-upload">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= Html::submitButton('上传', ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end() ?>
</div>