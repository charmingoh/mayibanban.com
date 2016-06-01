<?php


use frontend\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $siteLog common\models\SiteLog */
/* @var $siteLogProvider \yii\data\ActiveDataProvider */
$this->title = '更新日志' . '-' . Yii::$app->name;
?>
<?= Html::cssFile('@web/static/site/log.css'); ?>
<div class="site-log-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            <p class="panel-title">
                今天上线了 <strong><?= Html::encode($siteLog->todayLogCount); ?></strong> 次,
                历史累计迭代 <strong><?= Html::encode($siteLogProvider->totalCount); ?></strong> 个版本
            </p>
        </div>
        <div class="panel-body">
            <p>为什么要单独做一个页面记录网站的更新日志呢？这个想法来自 YC 创始人 Paul Graham 写的<code>《黑客与画家》</code>，
                里面讲到他的第一家创业公司 Viaweb 一天可能上线 3 到 5 个版本，他要求工程师写的代码能够立马上线看到效果，
                而不是积累多少功能就上线，然后取一个所谓的版本号。在他看来，<code>版本号是公关伎俩</code>。这样做也能大大提升工程师的<code>成就感</code>。
                这也是我为什么做这样一个东西，细心的你会发现，加粗的字体不是所谓的<code>版本号</code>，而是<code>上线次数</code>。</p>
        </div>

        <?php /*Pjax::begin([
            'scrollTo'     => 0,
            'formSelector' => false,
            'linkSelector' => '.pagination a'
        ]);*/ ?>
        <?= ListView::widget([
            'dataProvider' => $siteLogProvider,
            'itemOptions'  => ['class' => 'list-group-item'],
            'summary'      => false,
            'itemView'     => 'site-log-item',
            'options'      => ['class' => 'list-group'],
            'pager'        => [
                'class' => \kop\y2sp\ScrollPager::className(),
                'container' => '.list-group',
                'item' => '.list-group-item',
                'triggerOffset' => 0,
            ],
        ]); ?>
        <?php //Pjax::end(); ?>
    </div>
</div>

<?php
if (!Yii::$app->user->isGuest && Yii::$app->user->id == 1) {
    echo $this->render('site-log-create', ['model' => $siteLog]);
}
?>
