<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/4/27
 * Time: 下午2:13
 */
/* @var $model \common\models\SiteLog */
use frontend\helpers\Html;
use yii\helpers\Url;
$user = $model->createUser;
?>

<div class="site-log-item media">
    <div class="media-left">
        <a href="<?= Url::to(['profile/view', 'alias' => $user->alias]) ?>" title="<?= Html::encode($user->name); ?>" data-toggle="tooltip" data-placement="top">
            <?= Html::img($user->avatar == null ? '/static/img/default_avatar.png' : $user->avatar,
                [
                    'class' => 'media-object user-avatar',
                    'alt'   => $user->name,
                ]); ?>
        </a>
    </div>

    <div class="media-body">
        <h4 class="media-heading">
            第 <?= Html::encode($model->id); ?> 次上线
        </h4>
        <p class="detail">
            <?= nl2br(Html::encode($model->detail)); ?>
        </p>
        <p class="other-info">
            <i class="fa fa-tag" aria-hidden="true"></i>
            <span class="version">
                <?= Html::encode($model->version); ?>
            </span>
            <span>&nbsp;</span>
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            <span class="time">
                <?= Yii::$app->formatter->asRelativeTime($model->created_at); ?>
            </span>
        </p>
    </div>
</div>
