<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $localAuth common\models\LocalAuth */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $localAuth->password_reset_token]);
?>
你好 <?= $localAuth->user->name ?>,

请点击以下链接重置密码:

<?= $resetLink ?>

如果链接打不开,请复制链接到浏览器新标签页打开.
