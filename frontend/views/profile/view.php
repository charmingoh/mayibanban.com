<?php
/**
 *
 * @var \common\models\Profile $profile
 * @var \yii\data\ActiveDataProvider $threeAnswers
 * @var \yii\data\ActiveDataProvider $threeQuestions
 * @var \yii\web\View $this
 */
use frontend\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = $profile->name . '的主页 - ' . Yii::$app->name;
?>

<?= $this->render('base', ['profile' => $profile, 'active' => 'view']); ?>

<div class="panel panel-default<?= $threeAnswers->count == 0 ? ' hide' : ''; ?>">
    <div class="panel-heading">
        回答
        <a class="pull-right text-muted" href="<?= Url::to(['profile/answers', 'alias' => $profile->alias]); ?>">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
    </div>

    <?= ListView::widget([
        'dataProvider' => $threeAnswers,
        'options' => ['class' => 'list-group'],
        'itemView' => '_item-answer',
        'itemOptions' => ['class' => 'list-group-item'],
        'summary' => false,
    ]); ?>
</div>

<div class="panel panel-default<?= $threeQuestions->count == 0 ? ' hide' : ''; ?>">
    <div class="panel-heading">
        提问
        <a class="pull-right text-muted" href="<?= Url::to(['profile/asks', 'alias' => $profile->alias]); ?>">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
    </div>

    <?= ListView::widget([
        'dataProvider' => $threeQuestions,
        'options' => ['class' => 'list-group'],
        'itemView' => '_item-question',
        'itemOptions' => ['class' => 'list-group-item'],
        'summary' => false,
    ]); ?>
</div>

<div class="panel panel-default hide">
    <div class="panel-heading">
        最新动态
    </div>
    <div class="list-group">
        <div class="list-group-item">
            <div class="user-action-item-summary text-muted">
                Charming 关注了问题
                <p class="pull-right">
                    21 小时前
                </p>
            </div>
            <div>
                <strong><a href="/question/70" target="_blank">如何优雅地去成都城北区?</a></strong>
            </div>
        </div>
        <div class="list-group-item">
            <div class="user-action-item-summary text-muted">
                Charming 赞同了回答
                <p class="pull-right">
                    21 小时前
                </p>
            </div>
            <div>
                <strong><a href="/question/70" target="_blank">如何优雅地去成都城北区?</a></strong>
            </div>
            <p>
                Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.
            </p>
        </div>
        <div class="list-group-item">
            <h4 class="list-group-item-heading">List group item heading</h4>
            <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
        </div>
        <div class="list-group-item">
            <h4 class="list-group-item-heading">List group item heading</h4>
            <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
        </div>
    </div>
</div>

