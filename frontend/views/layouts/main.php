<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
//use frontend\assets\AppAsset;
use common\widgets\Alert;

//AppAsset::register($this);
//yii.js
\yii\web\YiiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <meta name="baidu-site-verification" content="lsNk8xCri6" />
    <meta property="qc:admins" content="152052643012162166375" />
    <meta property="wb:webmaster" content="ab8d1cd36848fa98" />
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="/favicon.ico">
    <?php $this->head() ?>
    <link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/pace/1.0.2/themes/blue/pace-theme-minimal.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/pace/1.0.2/pace.min.js"></script>
    <?= Html::cssFile('@web/static/layouts/main.css'); ?>
    <?= Html::cssFile('@web/libs/notie/3.2.0/notie.css'); ?>
    <script>var _hmt=_hmt||[];(function(){var hm=document.createElement("script");hm.src="//hm.baidu.com/hm.js?23a40d4503a7e0f333c26fa84b56b9ce";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm,s);})();</script>
</head>
<body>
<?php $this->beginBody() ?>
<?= Html::jsFile('@web/static/layouts/main.js') ?>

<div class="wrap">
    <?php
    $brandLogo = Html::img('@web/static/img/logo.png', [
        'style' => 'display: inline;',
        'class' => 'mayibaban-img',
        'alt' => 'logo',
        'width' => 18,
        'height' => 18,
    ]);

    $brandText = Html::tag('span', '蚂蚁搬搬', ['class' => 'mayibanban-text']);

    NavBar::begin([
        'brandLabel' => $brandLogo . $brandText,
        'brandUrl'   => Yii::$app->homeUrl,
        'options'    => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    $leftMenuItems = [
        ['label' => '最新', 'url' => ['/question/index', 'sort' => 'latest']],
        ['label' => '热门', 'url' => ['/question/index', 'sort' => 'hottest']],
        ['label' => '未回答', 'url' => ['/question/index', 'sort' => 'unanswered']],
    ];
    if (Yii::$app->user->isGuest) {
        //$rightMenuItems[] = ['label' => '注册', 'url' => ['/user/signup']];
        $rightMenuItems[] = ['label' => '登录', 'url' => ['/user/login']];
    } else {
        $rightMenuItems[] = ['label' => '提问', 'url' => ['/question/create']];
        $rightMenuItems[] = [
            'label' => Yii::$app->user->identity->name,
            'items' => [
                ['label' => '我的主页', 'url' => ['/profile/view', 'alias' => Yii::$app->user->identity->alias]],
                ['label' => '我的收藏', 'url' => ['/profile/collections', 'alias' => Yii::$app->user->identity->alias]],
                '<li class="divider"></li>',
                ['label' => '退出登录', 'url' => ['/user/logout'], 'linkOptions' => ['data-method' => 'post']],
            ]
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items'   => $leftMenuItems,
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items'   => $rightMenuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-muted">
            <i class="fa fa-copyright" aria-hidden="true"></i>
            <?= Yii::$app->name . ' ' . date('Y') ?>
        </p>
        <p class="text-muted">
            <i class="fa fa-tag" aria-hidden="true"></i>
            <span><?= Yii::$app->version ?></span>
            <i class="fa fa-tags" aria-hidden="true"></i>
            <?= Html::a('更新日志', ['site/log'], ['class' => 'text-muted']); ?>
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <?= Html::a('关于我们', ['site/about'], ['class' => 'text-muted']); ?>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
<?= Html::jsFile('@web/libs/notie/3.2.0/notie.min.js'); ?>
</body>
</html>
<?php $this->endPage() ?>
