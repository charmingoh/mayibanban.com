<?php

/* @var $model \common\models\Question */
use frontend\helpers\Html;

/* @var $user \common\models\User */
$user = $model->createUser;
?>

<div class="question-index-item media">
    <?php
    if ($model->answer_count > 0) {
        echo Html::a(Html::tag('span', $model->answer_count . '  个回答', ['class' => 'badge']),
            ['/question/view', 'id' => $model->id],
            [
                'class'          => 'pull-right',
                'data-toggle'    => 'tooltip',
                'data-placement' => 'top',
                'title'          => $model->answer_count . '个回答',
            ]
        );
    }
    ?>

    <div class="media-left hidden-xs">
        <a href="<?=\yii\helpers\Url::to(['profile/view', 'alias' => $user->alias]) ?>"
           title="<?= Html::encode($user->name); ?>" data-toggle="tooltip" data-placement="top">
            <?= Html::img($user->avatar == null ? '/static/img/default_avatar.png' : $user->avatar,
                [
                    'class' => 'media-object user-avatar img-circle',
                    'alt'   => $user->name,
                ]); ?>
        </a>
    </div>

    <div class="media-body">
        <h4 class="media-heading">
            <?= Html::a(Html::encode($model->title), ['question/view', 'id' => $model->id]) ?>
        </h4>
        <p class="question-item-detail">
            <?= nl2br(Html::encode($model->detail)); ?>
        </p>
        <p class="question-item-info">
            <i class="fa fa-eye" aria-hidden="true"></i>
            <?= Html::encode($model->view_count); ?> |
            <i class="fa fa-rss" aria-hidden="true"></i>
            <?= Html::encode($model->follow_count); ?> |
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            <?=
            Yii::$app->formatter->asRelativeTime($model->created_at);
            ?>
        </p>
    </div>
</div>


