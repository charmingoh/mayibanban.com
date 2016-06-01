<?php
/**
 * @var \yii\data\ActiveDataProvider $answerProvider
 * @var \common\models\Question $question
 */
use frontend\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$sortParam = Yii::$app->request->getQueryParam('sort');
?>
<?= Html::cssFile('@web/static/answer/index.css') ?>
<?= Html::jsFile('@web/static/answer/index.js'); ?>
<div class="answer-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            共有 <strong><?= Html::encode($question->answer_count); ?></strong> 个回答
            <p class="pull-right text-muted">
                <span>排序:&nbsp;&nbsp;</span>
                <a class="<?= $sortParam == 'latest' ? '' : 'text-muted'; ?>"
                   href="<?= Url::to(['question/view', 'id' => $question->id]); ?>">
                    赞同数
                </a>
                <span>&nbsp;|&nbsp;</span>
                <a class="<?= $sortParam == 'latest' ? 'text-muted' : ''; ?>"
                   href="<?= Url::to(['question/view', 'id' => $question->id, 'sort' => 'latest']); ?>">
                    回答时间
                </a>
            </p>
        </div>
        <?php Pjax::begin([
            'scrollTo' => 0,
            'formSelector' => false,
            'linkSelector' => '.pagination a',
        ]); ?>
        <?= ListView::widget([
            'dataProvider' => $answerProvider,
            'itemOptions' => ['class' => 'list-group-item'],
            'summary' => false,
            'itemView' => '_item',
            'options' => ['class' => 'list-group'],
        ]); ?>
        <?php Pjax::end(); ?>
        <script>
            $(document).on('pjax:success', function() {
                initAnswerActions();
            })
        </script>
    </div>
</div>
