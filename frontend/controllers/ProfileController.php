<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/5/15
 * Time: 下午4:55
 */

namespace frontend\controllers;


use common\models\Profile;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    public function actionView($alias)
    {
        $profile = Profile::createByAlias($alias);
        if ($profile === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $profile->addViewCount();

        $threeAnswers = new ActiveDataProvider([
            'query'      => $profile->getAnswers()->orderBy(['created_at' => SORT_DESC])->limit(3)->offset(0),
            'pagination' => false,
        ]);

        $threeQuestions = new ActiveDataProvider([
            'query'      => $profile->getQuestions()->orderBy(['created_at' => SORT_DESC])->limit(3)->offset(0),
            'pagination' => false,
        ]);

        return $this->render('view', [
            'profile'        => $profile,
            'threeAnswers'   => $threeAnswers,
            'threeQuestions' => $threeQuestions,
        ]);
    }

    public function actionAsks($alias)
    {
        $profile = Profile::createByAlias($alias);
        if ($profile === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $questions = new ActiveDataProvider([
            'query' => $profile->getQuestions(),
            'sort'  => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]
        ]);

        return $this->render('asks', [
            'profile'   => $profile,
            'questions' => $questions,
        ]);
    }

    public function actionAnswers($alias)
    {
        $profile = Profile::createByAlias($alias);
        if ($profile === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $answers = new ActiveDataProvider([
            'query' => $profile->getAnswers(),
            'sort'       => [
                'defaultOrder' => [
                    'latest' => SORT_ASC,
                ],
                'attributes'   => [
                    'latest'     => [
                        'asc'     => ['created_at' => SORT_DESC],
                        'desc'    => ['created_at' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'   => '回答时间',
                    ],
                    'popular'     => [
                        'asc'     => ['like_count' => SORT_DESC],
                        'desc'    => ['like_count' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'   => '赞同数',
                    ],
                ],
            ],
        ]);

        return $this->render('answers', [
            'profile' => $profile,
            'answers' => $answers,
        ]);
    }

    public function actionCollections($alias)
    {
        $profile = Profile::createByAlias($alias);
        if ($profile === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $answers = new ActiveDataProvider([
            'query' => $profile->getFavoriteAnswers(),
        ]);

        return $this->render('collections', [
            'profile' => $profile,
            'answers' => $answers,
        ]);
    }
}