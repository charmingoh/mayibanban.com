<?php
/* @var \common\models\Question $model*/
use frontend\helpers\Html;
?>

<div class="media">
    <div class="media-left">
        <div style="background-color: #ddd; width: 50px; height: 42px; border-radius: 4px;">
            <p class="text-center" style="margin: 0px; font-size: 16px;">
                <strong><?= $model->view_count; ?></strong>
            </p>
            <p class="text-center" style="margin: 0px;">浏览</p>
        </div>
    </div>
    <div class="media-body">
        <h4 class="media-heading">
            <?= Html::a($model->title, ['question/view', 'id' => $model->id], ['target' => '_blank']) ?>
        </h4>
        <p class="question-item-detail" style="margin: 0px;">
            <?= Html::encode($model->detail); ?>
        </p>
    </div>
</div>
