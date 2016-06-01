<?php
namespace frontend\controllers;

use common\components\oauth\OAuthHandler;
use common\models\OAuth;
use common\models\UserAction;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * User controller
 */
class UserController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth'    => [
                'class'           => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new OAuthHandler($client))->handle();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionOldLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('oldLogin', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $notLinkedWithSocial = OAuth::findOne(['user_id' => Yii::$app->user->identity->getId()]) == null;
            if ($notLinkedWithSocial) {
                Yii::$app->getSession()->setFlash('warning',
                    '请及时绑定微博或 QQ 登录, 更方便, 后续将不再支持邮箱登录, 点击: <a href="/user/auth?authclient=QQ">绑定 QQ</a> | <a href="/user/auth?authclient=Weibo">绑定微博</a>');
            }

            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '重置密码邮件发送成功!请到您的邮箱查看.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '抱歉,暂时无法为您这个注册邮箱重置密码.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '重置密码成功.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * user like/follow/favorite a target
     *
     */
    public function actionDo($action, $type, $id)
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired(false, false);
            //return $this->jsonResult(false, null, '请先登录');
        }

        $userAction = new UserAction();
        $userAction->action = $action;
        $userAction->target_type = $type;
        $userAction->target_id = $id;

        if (!$userAction->validate() || !$userAction->targetExists()) {
            throw new NotFoundHttpException();
        }

        if ($userAction->hasDone()) {
            //$userAction->delete();
            return $this->jsonResult(false, null, '你已经操作过了~');
        }

        if ($userAction->save()) {
            $userAction->updateTargetCounter();
            return $this->jsonResult(true);
        } else {
            return $this->jsonResult(false, null, '操作失败');
        }
    }
}
