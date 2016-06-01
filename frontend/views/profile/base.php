<?php
/**
 *
 * @var \common\models\Profile $profile
 * @var String $active
 */
use frontend\helpers\Html;
use yii\helpers\Url;

?>
<?= Html::cssFile('@web/static/profile/base.css'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <?= Html::img($profile->avatar, [
                    'class' => 'profile-user-avatar',
                    'alt'   => $profile->name,
                ]); ?>
            </div>
            <div class="media-body">
                <h4 class="media-heading">
                    <?= Html::encode($profile->name); ?>
                </h4>
                <p>
                    <?= Html::encode($profile->description); ?>
                </p>
                <div class="text-muted">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;注册于
                    <?= Yii::$app->formatter->asDate($profile->created_at) ?>
                    <?php
                    switch ($profile->gender) {
                        case 'male':
                            echo '&nbsp;&nbsp;<i class="fa fa-mars" aria-hidden="true"></i>';
                            break;
                        case 'female':
                            echo '&nbsp;&nbsp;<i class="fa fa-venus" aria-hidden="true"></i>';
                            break;
                        default:
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        获得&nbsp;&nbsp;
        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
        <strong><?= $profile->getUserLikeCount(); ?></strong>&nbsp;赞同
        &nbsp;
        <i class="fa fa-bookmark" aria-hidden="true"></i>
        <strong><?= $profile->getUserFavoriteCount(); ?></strong>&nbsp;收藏
        <p class="text-muted pull-right">
            <i class="fa fa-eye" aria-hidden="true"></i>
            <strong><?= Html::encode($profile->view_count); ?></strong>
        </p>
    </div>
</div>

<ul class="nav nav-tabs" id="profile-nav">
    <li class="<?= $active == 'view' ? 'active' : ''; ?>">
        <a href="<?= Url::to(['profile/view', 'alias' => $profile->alias]); ?>">
            主页
        </a>
    </li>
    <li class="<?= $active == 'asks' ? 'active' : ''; ?>">
        <a href="<?= Url::to(['profile/asks', 'alias' => $profile->alias]); ?>">
            提问 <span class="text-muted"><?= Html::encode($profile->getQuestions()->count()); ?></span>
        </a>
    </li>
    <li class="<?= $active == 'answers' ? 'active' : ''; ?>">
        <a href="<?= Url::to(['profile/answers', 'alias' => $profile->alias]); ?>">
            回答 <span class="text-muted"><?= Html::encode($profile->getAnswers()->count()); ?></span>
        </a>
    </li>
    <li class="<?= $active == 'collections' ? 'active' : ''; ?>">
        <a href="<?= Url::to(['profile/collections', 'alias' => $profile->alias]); ?>">
            收藏 <span class="text-muted"><?= Html::encode($profile->getFavoriteAnswersCount()); ?></span>
        </a>
    </li>
</ul>