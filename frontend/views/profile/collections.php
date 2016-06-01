<?php
/**
 *
 * @var \common\models\Profile $profile
 * @var \yii\web\View $this
 */
use frontend\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = $profile->name . '的收藏 - ' . Yii::$app->name;
?>
<?= $this->render('base', ['profile' => $profile, 'active' => 'collections']); ?>

<div class="panel panel-default<?= $answers->count == 0 ? ' hide' : ''; ?>">
    <div class="panel-heading">
        <?= Html::a($profile->name, ['profile/view', 'alias' => $profile->alias]); ?>&nbsp;的回答
        <a class="pull-right" href="<?= Url::to(['profile/view', 'alias' => $profile->alias]); ?>">
            返回个人主页
        </a>
    </div>

    <?php Pjax::begin([

    ]); ?>
    <?= ListView::widget([
        'dataProvider' => $answers,
        'options' => ['class' => 'list-group profile-answer-list'],
        'itemView' => '_item-answer',
        'itemOptions' => ['class' => 'list-group-item'],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>