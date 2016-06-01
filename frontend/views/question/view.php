<?php

use frontend\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $question common\models\Question */
/* @var $answerForm frontend\models\AnswerForm */
/* @var $answerProvider \yii\data\ActiveDataProvider */
$this->title = $question->title . ' - ' . Yii::$app->name;
/* @var $user \common\models\User */
$user = $question->createUser;
$actionArray = Yii::$app->user->isGuest ? [''] : $question->getUserActions(Yii::$app->user->identity->getId());
?>
<?= Html::cssFile('@web/static/question/view.css'); ?>
<?= Html::jsFile('@web/static/question/view.js'); ?>
<div class="question-view">
    <div class="panel panel-default">
        <div class="panel-body">
            <div>
                <a href="<?= Url::to(['profile/view', 'alias' => $user->alias]); ?>">
                    <?= Html::img($user->avatar == null ? '/static/img/default_avatar.png' : $user->avatar,
                        [
                            'class' => 'media-object user-avatar img-circle inline-block',
                            'alt'   => $user->name,
                        ]); ?>
                    <?= Html::encode($user->name) ?>
                </a>

                <span class="question-info pull-right">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    <?= Html::encode($question->view_count); ?> |
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <?=
                    Yii::$app->formatter->asRelativeTime($question->created_at);
                    ?>
                </span>
            </div>

            <div class="question-title">
                <h5><?= Html::encode($question->title) ?></h5>
            </div>

            <?= nl2br(Html::encode($question->detail)); ?>
        </div>
        <div class="panel-footer">
            <?php if(in_array('follow', $actionArray)): ?>
                <a class="question-follow-action text-muted" data-id="<?= Html::encode($question->id) ?>">
                    <i class="fa fa-rss" aria-hidden="true"></i> 已关注
                </a>
            <?php else: ?>
                <a class="question-follow-action" data-id="<?= Html::encode($question->id) ?>">
                    <i class="fa fa-rss" aria-hidden="true"></i> 关注
                </a>
            <?php endif ?>
            <span class="text-muted">&nbsp;·&nbsp;共有&nbsp;</span>
            <span class="text-muted question-follow-count"><?= Html::encode($question->follow_count); ?></span>
            <span class="text-muted">&nbsp;人关注此问题</span>
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->getId() == $question->created_by): ?>
                <div class="pull-right">
                    <a class="text-muted" href="<?= Url::to(['/question/update', 'id' => $question->id]); ?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;修改
                    </a>
                    <?= $question->answer_count == 0 ? Html::a('&nbsp;&nbsp;<i class="fa fa-trash" aria-hidden="true"></i>',
                        ['/question/delete', 'id' => $question->id], ['class' => 'text-muted', 'data-method' => 'post']) : ''; ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <?php
    if ($question->answer_count > 0) {
        echo $this->render('@frontend/views/answer/index', [
            'question'       => $question,
            'answerProvider' => $answerProvider,
        ]);
    }
    ?>

    <?= $this->render('@frontend/views/answer/create', [
        'question'   => $question,
        'answerForm' => $answerForm,
    ]); ?>
</div>
