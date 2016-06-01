<?php
/**
 *
 * @var \common\models\Profile $profile
 * @var \yii\data\ActiveDataProvider $answers
 * @var \yii\web\View $this
 */
use frontend\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = $profile->name . '的回答 - ' . Yii::$app->name;
$sortParam = Yii::$app->request->getQueryParam('sort');
?>
<?= $this->render('base', ['profile' => $profile, 'active' => 'answers']); ?>

<div class="panel panel-default<?= $answers->count == 0 ? ' hide' : ''; ?>">
    <div class="panel-heading">
        <?= Html::a($profile->name, ['profile/view', 'alias' => $profile->alias]); ?>&nbsp;的回答
        <p class="pull-right text-muted">
            <span>排序:&nbsp;&nbsp;</span>
            <a class="<?= $sortParam == 'popular' ? 'text-muted' : ''; ?>"
               href="<?= Url::to(['profile/answers', 'alias' => $profile->alias, 'sort' => 'popular']); ?>">
                赞同数
            </a>
            <span>&nbsp;|&nbsp;</span>
            <a class="<?= $sortParam == 'popular' ? '' : 'text-muted'; ?>"
               href="<?= Url::to(['profile/answers', 'alias' => $profile->alias]); ?>">
                回答时间
            </a>
        </p>
    </div>

    <?php Pjax::begin([
        'scrollTo'     => 0,
        'formSelector' => false,
        'linkSelector' => '.pagination a'
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