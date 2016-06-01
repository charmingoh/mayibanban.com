<?php

namespace frontend\controllers;

use common\models\Question;
use frontend\helpers\HtmlContent;
use frontend\models\AnswerForm;
use Yii;
use common\models\Answer;
use frontend\models\AnswerSearch;
use yii\filters\AccessControl;
use yii\validators\UrlValidator;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AnswerController implements the CRUD actions for Answer model.
 */
class AnswerController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'create', 'update', 'delete', 'title'],
                'rules' => [
                    [
                        'actions' => ['create', 'title'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view', 'update', 'delete'],
                        'allow'   => false,
                        'roles'   => ['@', '?'],
                    ]
                ]
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'title'  => ['POST'],
                    'create' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Answer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AnswerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Answer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Answer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AnswerForm();

        if ($model->load(Yii::$app->request->post()) && ($answer = $model->create())) {
            Yii::$app->session->setFlash('success', '成功添加回答');
            $question = Question::findOne($answer->question_id);
            $question->answer_count += 1;
            $question->save(false);
            //Question::updateAllCounters(['answer_count' => 1], ['id' => $answer->question_id]);
        } else {
            Yii::$app->session->setFlash('warning', '创建回答失败');
        }
        return $this->redirect(['/question/view', 'id' => $answer->question_id]);
    }

    /**
     * Updates an existing Answer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Answer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUrl($id)
    {
        $answer = $this->findModel($id);
        $answer->updateCounters(['view_count' => 1]);
        return $this->redirect($answer->url);
    }

    public function actionTitle()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException;
        }

        $url = Yii::$app->request->post('url');
        $urlValidator = new UrlValidator();
        if (!$urlValidator->validate($url)) {
            return $this->jsonResult();
        }
        
        $data = [
            'title' => HtmlContent::getTitleByUrl($url)
        ];

        return $this->jsonResult(true, $data);
    }

    /**
     * Finds the Answer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Answer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Answer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
