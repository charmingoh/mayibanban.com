<?php

namespace frontend\controllers;

use frontend\models\AnswerForm;
use frontend\models\QuestionForm;
use Yii;
use common\models\Question;
use frontend\models\QuestionSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['create', 'update', 'delete'],
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $question = $this->findModel($id);
        $question->updateCounters(['view_count' => 1]);

        $answerProvider = new ActiveDataProvider([
            'query'      => $question->getAnswers(),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'popular' => SORT_ASC,
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
            ]
        ]);
        $answerForm = new AnswerForm();

        return $this->render('view', [
            'question'       => $question,
            'answerForm'         => $answerForm,
            'answerProvider' => $answerProvider,
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QuestionForm();

        if ($model->load(Yii::$app->request->post()) && ($question = $model->create())) {
            Yii::$app->session->setFlash('success', '创建问题成功');
            return $this->redirect(['view', 'id' => $question->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $notCreateUser = Yii::$app->user->identity->getId() != $model->created_by;
        if ($notCreateUser) {
            throw new UnauthorizedHttpException('你没有权限修改本问题');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $notCreateUser = Yii::$app->user->identity->getId() != $model->created_by;
        if ($notCreateUser) {
            throw new UnauthorizedHttpException('你没有权限删除本问题');
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
