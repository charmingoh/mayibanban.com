<?php

use frontend\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$sort = $dataProvider->getSort();
$sortParam = Yii::$app->request->getQueryParam('sort');
$sortName = '最近更新的问题';
switch ($sortParam) {
    case 'latest':
        $sortName = '最新问题';
        break;
    case 'hottest':
        $sortName = '热门问题';
        break;
    case 'unanswered':
        $sortName = '未回答的问题';
        break;
}

$this->title = $sortName . ' - ' . Yii::$app->name;
?>
<?= Html::cssFile('@web/static/question/index.css'); ?>
<?= Html::jsFile('@web/static/question/index.js'); ?>
<div class="question-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title pull-left">
                <?= $sortName ?>
            </div>
            <div class="filter pull-right">
                <?php /*'查看:' . $sort->link('hottest') . ' / ' . $sort->link('latest') . ' / ' . $sort->link('unanswered');*/ ?>
            </div>
        </div>

        <?php Pjax::begin([
            'scrollTo'     => 0,
            'formSelector' => false,
            'linkSelector' => '.pagination a'
        ]); ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions'  => ['class' => 'list-group-item'],
            'summary'      => false,
            'itemView'     => '_item',
            'options'      => ['class' => 'list-group'],
            'emptyText'    => '暂时没有未回答的问题, 快去提问吧~',
        ]) ?>
        <?php Pjax::end() ?>

        <!--<div class="panel-body"></div>
        <div class="panel-footer"></div>-->
    </div>
</div>


