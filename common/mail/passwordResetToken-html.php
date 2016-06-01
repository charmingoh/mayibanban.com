<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $localAuth common\models\LocalAuth */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $localAuth->password_reset_token]);
?>
<div class="password-reset">
    <p>你好 <?= Html::encode($localAuth->user->name) ?>,</p>

    <p>请点击以下链接重置密码:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

    <p>如果链接打不开,请复制链接到浏览器新标签页打开.</p>
</div>
