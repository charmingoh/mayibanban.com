<?php
/**
 *
 * @var \common\models\Profile $profile
 * @var \yii\data\ActiveDataProvider $questions
 * @var \yii\web\View $this
 */
use frontend\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = $profile->name . '的提问 - ' . Yii::$app->name;
?>
<?= $this->render('base', ['profile' => $profile, 'active' => 'asks']); ?>

<div class="panel panel-default<?= $questions->count == 0 ? ' hide' : ''; ?>">
    <div class="panel-heading">
        <?= Html::a($profile->name, ['profile/view', 'alias' => $profile->alias]); ?>&nbsp;的提问
        <a class="pull-right" href="<?= Url::to(['profile/view', 'alias' => $profile->alias]); ?>">
            返回个人主页
        </a>
    </div>
    <?php Pjax::begin([
        'linkSelector' => '.pagination a',
        'formSelector' => false,
        'scrollTo' => 0,
    ]); ?>
    <?= ListView::widget([
        'dataProvider' => $questions,
        'options' => ['class' => 'list-group profile-question-list'],
        'itemView' => '_item-question',
        'itemOptions' => ['class' => 'list-group-item'],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
