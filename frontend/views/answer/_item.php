<?php

/**
 * @var $user \common\models\User
 * @var $model \common\models\Answer
 */
use frontend\helpers\Html;
use yii\helpers\Url;
$user = $model->user;
$actionArray = Yii::$app->user->isGuest ? [''] : $model->getUserActions(Yii::$app->user->identity->getId());
?>

<div class="media" id="<?= Html::encode('answer-' . $model->id); ?>">
    <div class="media-left">
        <div class="btn-group-vertical" role="group" aria-label="answer-options">
            <a class="btn btn-sm btn-default answer-like-action<?= in_array('like', $actionArray) ? ' done' : ''; ?>" type="button"
               data-id="<?= Html::encode($model->id); ?>" title="点赞">
                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                <span class="answer-like-count">
                    <?= Html::encode($model->like_count); ?>
                </span>
            </a>
            <a class="btn btn-xs btn-default answer-favorite-action<?= in_array('favorite', $actionArray) ? ' done' : ''; ?>" type="button"
               data-id="<?= Html::encode($model->id); ?>" title="有帮助,值得收藏">
                <i class="fa fa-bookmark" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <div class="media-body">
        <div class="hide">
            <a href="<?= Url::to(['profile/view', 'alias' => $user->alias]) ?>">
                <?= Html::img($user->avatar == null ? '/static/img/default_avatar.png' : $user->avatar,
                    [
                        'class' => 'media-object user-avatar img-circle inline-block',
                        'alt'   => $user->name,
                    ]); ?>
                <?= Html::encode($user->name) ?>
            </a>

            <span class="question-info pull-right">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <?= Html::encode($model->view_count); ?> |
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <?=
                Yii::$app->formatter->asRelativeTime($model->created_at);
                ?>
            </span>
        </div>

        <p class="answer-item-digest">
            <?= nl2br(Html::encode($model->digest)); ?>
        </p>
        <div class="answer-item-url list-group">
            <?= Html::a('<i class="fa fa-link" aria-hidden="true"></i> ' . Html::encode($model->title), ['answer/url', 'id' => $model->id], [
                'class' => 'list-group-item',
                'target' => '_blank'
            ]); ?>
        </div>
        <p class="answer-item-info">
            <i class="fa fa-eye" aria-hidden="true"></i>
            <?= Html::encode($model->view_count); ?> |
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            <?= Html::encode($model->favorite_count); ?> |
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            <?=
            Yii::$app->formatter->asRelativeTime($model->created_at);
            ?>
        </p>
    </div>

    <div class="media-right hidden-xs">
        <a href="<?= Url::to(['profile/view', 'alias' => $user->alias]) ?>"
           data-toggle="tooltip" data-placment="top" title="<?= Html::encode($user->name); ?>">
            <?= Html::img($user->avatar == null ? '/static/img/default_avatar.png' : $user->avatar,
                [
                    'class' => 'media-object user-avatar img-circle',
                    'alt'   => $user->name,
                ]); ?>
        </a>
    </div>
</div>


