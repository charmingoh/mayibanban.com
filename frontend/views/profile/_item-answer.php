<?php
/* @var \common\models\Answer $model*/
use frontend\helpers\Html;
?>

<div class="media">
    <div class="media-left">
        <div style="background-color: #eee; width: 50px; height: 42px; border-radius: 4px;">
            <p class="text-center" style="margin: 0px; font-size: 16px;">
                <strong><?= $model->like_count; ?></strong>
            </p>
            <p class="text-center" style="margin: 0px;">赞同</p>
        </div>
    </div>
    <div class="media-body">
        <h4 class="media-heading">
            <?= Html::a($model->title, ['answer/url', 'id' => $model->id], ['target' => '_blank']) ?>
        </h4>
        <p class="question-item-detail" style="margin: 0px;">
            <?= Html::encode($model->digest); ?>
        </p>
    </div>
</div>
